<?php
/**
 * This is a skeleton PEAR package attempt for the UC Berkeley Calendar Schema.
 * The class facilitates interaction between child objects and the database. It also
 * contains static functions useful throughout various frontends including
 * conversion between various formats:
 *    Database <--> iCalendar
 *    Database <--> hCalendar
 *    Database <--> xml conforming to The berkeley xml format.
 *
 * It also provides help to frontends that want to display information through a
 * template system.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

/**
 * Dependencies on DB_DataObject Error object, Cache_Lite, and MDB2
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN/Error.php';
require_once 'Cache/Lite.php';
require_once 'MDB2.php';

/**
 * The backend system object for the UNL UCBCN calendar system.
 * This object is the master object through which most calendar system
 * interactions take place.
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN
{
    /**
     * The template chosen to display in, defaults to default.
     * @var string $template
     */
    public $template;
    /**
     * The filesystem path to the templates.
     * @var string $template_path
     */
    public $template_path;
    /**
     * A string containing connection details in the format
     *  dbtype://user:pass@www.example.com:port/database
     * @var string $dsn
     */
    public $dsn;
    /**
     * Default calendar to use throughout the system.
     * @var int $default_calendar
     */
    public $default_calendar_id = 1;
    
    /**
     * input filters
     */
    protected static $input_filters = array();
    
    /**
     * output filters
     */
    protected static $output_filters = array();
    
    /**
     * Cache object for output caching
     * 
     * @var UNL_UCBCN_CachingService
     */
    static protected $cache;
    
    /**
     * Constructor for the UCBCN object, initializes member variables and sets up
     * connection details for the database.
     *
     * @param array $options Associative array of options to set for the class.
     */
    public function __construct($options=array('dsn'=>'@DSN@',
                                               'template_path'=>''))
    {
        $this->setOptions($options);
        $this->setupDBConn();
    }
    
    /**
     * This function initializes the information used by the database
     * connections.
     *
     * @return void
     */
    public function setupDBConn()
    {
        $dboptions = &PEAR::getStaticProperty('DB_DataObject', 'options');
        $dboptions = array(
            'database'          => $this->dsn,
            'schema_autoload'   => true,
            'autoload'          => true,
            'class_location'    => dirname(__FILE__).'/UCBCN',
            'require_prefix'    => dirname(__FILE__).'/UCBCN',
            'class_prefix'      => 'UNL_UCBCN_',
            'db_driver'         => 'MDB2',
            'quote_identifiers' => true
        );

        if ((substr($this->dsn, 0, 5)) == 'mysql') {
            // Use UTF-8 always
            $db = new DB_DataObject();
            $db->query('SET NAMES "utf8";');
            unset($db);
        }
    }
    
    /**
     * This function sets parameters for this class.
     *
     * @param array $options an associative array of options to set.
     *
     * @return void
     */
    public function setOptions($options)
    {
        global $_UNL_UCBCN;
        foreach ($options as $option=>$val) {
            if (property_exists($this, $option)) {
                switch($option) {
                case 'template':
                case 'template_path':
                case 'frontenduri':
                case 'manageruri':
                case 'uri':
                case 'default_calendar_id':
                case 'uriformat':
                    /*
                     * Set a global variable for this value, because this is
                     * is used in static functions.
                     */
                    $_UNL_UCBCN[$option] = $val;
                    break;
                }
                $this->$option = $val;
            } else {
                echo 'Warning: Trying to set unkown option ['
                     . $option . '] for object ' . get_class($this) . "\n";
            }
        }
    }
    
    /**
     * adds an input filter to this controller, the filter will be called whenever input is processed
     *
     * @param UNL_UCBCN_Filter $filter a filter object used for filtering input
     */
    public static function addInputFilter(UNL_UCBCN_Filter $filter)
    {
        self::$input_filters[] = $filter;
    }
    
    /**
     * adds an output filter to this controller, the filter will be called whenever output is sent
     *
     * @param UNL_UCBCN_Filter $filter a filter object used for filtering input
     */
    public static function addOutputFilter(UNL_UCBCN_Filter $filter)
    {
        self::$output_filters[] = $filter;
    }
    
    /**
     * This function gets the count of events for the given status.
     *
     * @param UNL_UCBCN_Calendar $calendar Calendar to check.
     * @param string             $status   [pending|posted|archived]
     *
     * @return int count
     */
    public function getEventCount(UNL_UCBCN_Calendar $calendar,$status='posted')
    {
        if ($status == 'posted') {
            $e              = UNL_UCBCN::factory('calendar_has_event');
            $e->calendar_id = $calendar->id;
            $e->whereAdd("status != 'pending'");
            $e->find();
        } else {
            $e              = UNL_UCBCN::factory('calendar_has_event');
            $e->calendar_id = $calendar->id;
            $e->status      = $status;
        }
        $count = 0;
        $e->find();
        $today = date('Y-m-d');
        while ($e->fetch()) {
            $rec           = UNL_UCBCN::factory('recurringdate');
            $rec->event_id = $e->event_id;
            $rec->whereAdd("ongoing = FALSE");
            $rec->whereAdd("unlinked = FALSE");
            if ($status == 'posted') {
                $rec->whereAdd("recurringdate >= '$today'");
            } else if ($status == 'archived') {
                $rec->whereAdd("recurringdate < '$today'");
            }
            $found = $rec->find();
            if ($found) {
                $count += $found;
            } else {
                $edt = UNL_UCBCN::factory('eventdatetime');
                $edt->event_id = $e->event_id;
                $edt->find(true);
                if ($edt->recurringtype == 'none' || $edt->recurringtype == '') {
                    if ($e->status == $status) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }
    
    /**
     * This function allows extended classes etc to get a DB DataObject
     * for the event table they need access to.
     *
     * @param string $table The name of the table in the database to receive a
     *                      DataObject for.
     *
     * @return mixed A object for the database table requested.
     */
    public static function factory($table)
    {
        return DB_DataObject::factory($table);
    }
    
    /**
     * creates a new user record and returns it.
     *
     * @param UNL_UCBCN_Account $account    The account to add this user under.
     * @param string            $uid        Unique id of the user to create
     * @param string            $uidcreated UID of the user who created this user.
     *
     * @return true or inserted id on success
     */
    public function createUser(UNL_UCBCN_Account $account,$uid,$uidcreated=null)
    {
        $values = array(
            'account_id'      => $account->id,
            'uid'             => $uid,
            'datecreated'     => date('Y-m-d H:i:s'),
            'uidcreated'      => $uidcreated,
            'datelastupdated' => date('Y-m-d H:i:s'),
            'uidlastupdated'  => $uidcreated);
        return $this->dbInsert('user', $values);
    }
    
    /**
     * This function is a general insert function,
     * given the table name and an assoc array of values,
     * it will return the inserted record.
     *
     * @param string $table  Name of the table
     * @param array  $values assoc array of values to insert.
     *
     * @return object on success, failed return value on failure.
     */
    public function dbInsert($table, $values)
    {
        $rec  = UNL_UCBCN::factory($table);
        $vars = get_object_vars($rec);
        foreach ($values as $var=>$value) {
            if (in_array($var, $vars)) {
                $rec->$var = $value;
            }
        }
        $result = $rec->insert();
        if (!$result) {
            return $result;
        }

        return $rec;
    }
    
    /**
     * Checks if a user has a given permission over the account.
     *
     * @param UNL_UCBCN_User     $user            User to check.
     * @param string             $permission_name The permission to check for.
     * @param UNL_UCBCN_Calendar $calendar        Calendar to check permissions on.
     *
     * @return bool true or false
     */
    public function userHasPermission(UNL_UCBCN_User $user,$permission_name,
        UNL_UCBCN_Calendar $calendar)
    {
        $permission       = UNL_UCBCN::factory('permission');
        $permission->name = $permission_name;
        if ($permission->find() && $permission->fetch()) {
            $user_has_permission                = UNL_UCBCN::factory('user_has_permission');
            $user_has_permission->permission_id = $permission->id;
            $user_has_permission->calendar_id   = $calendar->id;
            $user_has_permission->user_uid      = $user->uid;
            return $user_has_permission->find();
        }

        return new UNL_UCBCN_Error('The permission you requested to check for \''
                                   . $permission_name . '\', does not exist.');
    }
    
    /**
     * Simple function which displays the error to the end user.
     *
     * @param string $description Description of the error.
     *
     * @return void
     */
    public function showError($description)
    {
        self::displayRegion($description);
    }
    
    /**
     * The heart of the template/display portions of this system.
     * A simple function which renders the given content using a savant
     * formatted template based on the type of the object.
     * IE:     strings and ints get echoed
     *         objects use a corresponding savant template,
     *         arrays get rendered one by one
     *
     * For caching support the object being outputted must implement
     * three methods:
     * getCacheKey()                Return a unique string for the object/output.
     * preRun(bool $cache_hit)      Function which will be called before run
     *                              (implement cache hit recording here and header()
     *                              output)
     * run()                        This function must populate the object and get
     *                              it prepped for output.
     *
     * @param mixed $content The content to send out.
     * @param bool  $return  Whether to output or return the content.
     *
     * @return void
     */
    static public function displayRegion($mixed, $return = false)
    {
        if (is_array($mixed)) {
            return self::displayArray($mixed, $return);
        }
        
        if (is_object($mixed)) {
            return self::displayObject($mixed, $return);
        }
        
        if ($return) {
            return $mixed;
        }
        
        echo $mixed;
        return true;
    }
    
    static function displayArray($array, $return = false)
    {
        $output = '';
        foreach ($array as $mixed) {
            if ($return) {
                $output .= self::displayRegion($mixed, true);
            } else {
                self::displayRegion($mixed, false);
            }
        }
        
        if ($return) {
            return $output;
        }
        
        return true;
    }
    
    static function displayObject($object, $return = false)
    {
        if ($object instanceof UNL_UCBCN_Cacheable) {
            $key = $object->getCacheKey();
            
            // We have a valid key to store the output of this object.
            if ($key !== false && $data = self::getCachingService()->get($key)) {
                // Tell the object we have cached data and will output that.
                $object->preRun(true);
            } else {
                // Content should be cached, but none could be found.
                //flush();
                ob_start();
                $object->preRun(false);
                $object->run();
                
                if ($return) {
                    $data = self::sendObjectOutput($object, $return);
                } else {
                    self::sendObjectOutput($object, $return);
                    $data = ob_get_contents();
                }
                
                if ($key !== false) {
                    self::getCachingService()->save($data, $key);
                }
                ob_end_clean();
            }
            
            if ($object instanceof UNL_UCBCN_PostRunFiltering) {
                $data = $object->postRun($data);
            }
            
            if ($return) {
                return $data;
            }
            
            echo $data;
            return true;
        }
        
        return self::sendObjectOutput($object, $return);

    }
    
    /**
     * Prepares an object for output, and displays it with a corresponding template.
     *
     * This function is an output controller, which takes public member variables
     * from an object and populates a Savant template with equivalent member
     * variables.
     *
     * @param mixed $content Object with content to send out.
     *
     * @return void
     */
    static protected function sendObjectOutput($object, $return = false)
    {
        global $_UNL_UCBCN;
        include_once 'Savant3.php';
        $savant = new Savant3();
        if (!empty($_UNL_UCBCN['template_path'])) {
            $savant->addPath('template', $_UNL_UCBCN['template_path']);
        } else {
            $savant->addPath('template', 'templates/default');
            if ($_UNL_UCBCN['template'] != 'default') {
                $savant->addPath('template', 'templates/'.$_UNL_UCBCN['template']);
            }
        }
        $savant->assign($object);
        if ($object instanceof ArrayAccess) {
            foreach ($object->toArray() as $key=>$val) {
                $savant->$key = $val;
            }
        }
        if ($object instanceof Exception) {
            $savant->code    = $object->getCode();
            $savant->line    = $object->getLine();
            $savant->file    = $object->getFile();
            $savant->message = $object->getMessage();
            $savant->trace   = $object->getTrace();
        }
        $templatefile = self::getTemplateFilename(get_class($object));
        if ($return) {
            return $savant->fetch($templatefile);
        }
        $savant->display($templatefile);
        return true;
    }
    
    /**
     * This function adds the given permission for the user.
     *
     * @param string $uid           Username to add permission for.
     * @param int    $calendar_id   ID of the calendar to add permission for.
     * @param int    $permission_id Permission id you wish to add for the person.
     *
     * @return mixed ID on success false on error.
     */
    public function grantPermission($uid,$calendar_id,$permission_id)
    {
        $values = array(
                        'calendar_id'   => $calendar_id,
                        'user_uid'      => $uid,
                        'permission_id' => $permission_id
                        );
        return UNL_UCBCN::dbInsert('user_has_permission', $values);
    }
    
    /**
     * This function creates a calendar account.
     *
     * @param array $values assoc array of field values for the account.
     *
     * @return mixed ID on success false on error.
     */
    public function createAccount($values = array())
    {
        $defaults = array(
                'datecreated'     => date('Y-m-d H:i:s'),
                'datelastupdated' => date('Y-m-d H:i:s'),
                'sponsor_id'      => 1);
        $values   = array_merge($defaults, $values);
        return $this->dbInsert('account', $values);
    }
    
    /**
     * Adds an event to a calendar.
     *
     * @param UNL_UCBCN_Calendar $calendar UNL_UCBCN_Calendar object.
     * @param UNL_UCBCN_Event    $event    The event to add to the calendar.
     * @param string             $status   [pending|posted|archived]
     * @param UNL_UCBCN_User     $user     User adding this event to a calendar.
     * @param string             $source   Where is this coming from?
     *
     * @return object UNL_UCBCN_Account_has_event
     */
    public function addCalendarHasEvent(UNL_UCBCN_Calendar $calendar,
        UNL_UCBCN_Event $event, $status, UNL_UCBCN_User $user, $source=null)
    {
        return $calendar->addEvent($event, $status, $user, $source);
    }
    
    /**
     * Checks whether a calendar has an event or not.
     *
     * @param UNL_UCBCN_Calendar $calendar Calendar to check
     * @param UNL_UCBCN_Event    $event    Event to check if exists on the calendar.
     *
     * @return bool|object false on error, object on success.
     */
    public function calendarHasEvent(UNL_UCBCN_Calendar $calendar, UNL_UCBCN_Event $event)
    {
        $che              = UNL_UCBCN::factory('calendar_has_event');
        $che->calendar_id = $calendar->id;
        $che->event_id    = $event->id;
        if ($che->find()) {
            $che->fetch();
            return $che;
        }

        return false;
    }
    
    /**
     * This function returns a object for the user with
     * the given uid.
     * If a record does not exist, one is inserted then returned.
     *
     * @param string $uid The unique user identifier to get object for (username).
     *
     * @return UNL_UCBCN_User
     */
    public function getUser($uid)
    {
        $user      = UNL_UCBCN::factory('user');
        $user->uid = $uid;

        if ($user->find()) {
            $user->fetch();
            return $user;
        }

        if (!isset($this->account)) {
            // No account is currently set, create one for this user.
            $values  = array('name' => ucfirst($user->uid).' Calendar Manager');
            $this->account = $this->createAccount($values);
        }

        if (!isset($this->user)) {
            // No current user... this user has created his own user entry.
            $created_by = $uid;
        } else {
            // Another user has created this user.
            $created_by = $this->user->uid;
        }
        return $this->createUser($this->account, $uid, $created_by);
    }
    
    /**
     * Gets the account record(s) for the user
     *
     * @param UNL_UCBCN_User $user User to get account for.
     *
     * @return object UNL_UCBCN_Account on success UNL_UCBCN_Error on error.
     */
    public function getAccount(UNL_UCBCN_User $user)
    {
        $account     = UNL_UCBCN::factory('account');
        $account->id = $user->account_id;

        if ($account->find() && $account->fetch()) {
            return $account;
        }

        // No account exists!
        return new UNL_UCBCN_Error('No Account exists for the given user.');
    }
    
    /**
     * Gets the calendar(s) for the given account that the given user has permission
     * to. Optionally the user can be redirected on creation of a new calendar.
     *
     * @param UNL_UCBCN_User    $user         User to get the calendar for
     * @param UNL_UCBCN_Account $account      Account to get calendar for.
     * @param bool              $return_false If true, will return false if no
     *                                        account exists, if false it invokes
     *                                        createCalendar.
     * @param string            $redirecturl  A url to redirect on creation of a new
     *                                        record. If set the user will be
     *                                        redirected, otherwise the account will
     *                                        be returned.
     *
     * @return UNL_UCBCN_Calendar object on success false if no calendar exists and
     *                            $return_false paramter was passed as true.
     */
    public function getCalendar(UNL_UCBCN_User $user, UNL_UCBCN_Account $account,
        $return_false = true, $redirecturl=null)
    {
        
        $mdb2 = $account->getDatabaseConnection();
        $res =& $mdb2->query('SELECT calendar.id FROM calendar,user_has_permission
                            WHERE user_has_permission.user_uid = \''.$user->uid.'\'
                            AND user_has_permission.calendar_id = calendar.id
                            GROUP BY calendar.id');
        if (!(PEAR::isError($res)) && ($res->numRows() > 0)) {
            $row      = $res->fetchRow();
            $calendar = UNL_UCBCN::factory('calendar');
            $calendar->get($row[0]);
            return $calendar;
        }

        // No Calendar exists for the given account...
        if ($return_false == true) {
            return false;
        }

        // Create a new calendar and account and return the calendar.
        $values      = array(
                    'name'           => ucfirst($user->uid).'\'s Event Publisher!',
                    'shortname'      => $user->uid,
                    'uidcreated'     => $user->uid,
                    'uidlastupdated' => $user->uid,
                    'account_id'     => $account->id);
        $calendar    = $this->createCalendar($values);
        $permissions = UNL_UCBCN::factory('permission');
        //$permissions->whereAdd('name LIKE "Event%"');
        // grant all permissions to this new user for this new calendar.
        if (!$calendar->addUser($user)) {
            // Setup default permissions...?
            return new UNL_UCBCN_Error('No permissions could be added for '
                                    . 'the new user! Permissions need to'
                                    . ' be added to the permission table.');
        }

        if (isset($redirecturl)) {
            $this->localRedirect($redirecturl);
        }

        return $calendar;

    }
    
    /**
     * This function creates a calendar for an account.
     *
     * @param array $values assoc array of field values for the calendar.
     *
     * @return mixed int ID of calendar record on success false on error.
     */
    public function createCalendar($values = array())
    {
        $defaults = array(
			                'datecreated'     => date('Y-m-d H:i:s'),
			                'datelastupdated' => date('Y-m-d H:i:s'),
			                'uidlastupdated'  => 'system',
			                'uidcreated'      => 'system');
        $values   = array_merge($defaults, $values);
        return $this->dbInsert('calendar', $values);
    }
    
    /**
     * Redirects to the given full or partial URL.
     * will turn the given url into an absolute url
     * using the above getURL() function. This function
     * does not return.
     *
     * @param string $url          Full/partial url to redirect to
     * @param bool   $keepProtocol Keep the https protocol or to force HTTP?
     *
     * @return void
     */
    public function localRedirect($url, $keepProtocol = true)
    {
        $url = self::getAbsoluteURL($url);
        if ($keepProtocol == false) {
            $url = preg_replace('/^https/', 'http', $url);
        }
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Returns an absolute URL
     *
     * @param string $relativeUri All/part of a url
     * @param string $baseUri     The URI to use as the base
     *
     * @return string      Full url
     */
    public static function getAbsoluteURL($relativeUri, $baseUri = null)
    {
        if (filter_var($relativeUri, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            // URL is already absolute
            return $relativeUri;
        }

        if (!isset($baseUri)) {
            $baseUri = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'); 
            $baseUri .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $baseUri = substr($baseUri, -1*strlen($_SERVER['QUERY_STRING']));
            }
        }

        $new_base_url = $baseUri;
        $base_url_parts = parse_url($baseUri);

        if (substr($baseUri, -1) != '/') {
            $path = pathinfo($base_url_parts['path']);
            $new_base_url = substr($new_base_url, 0, strlen($new_base_url)-strlen($path['basename']));
        }

        $new_txt = '';

        if (substr($relativeUri, 0, 1) == '/') {
            $new_base_url = $base_url_parts['scheme'].'://'.$base_url_parts['host'];
        }
        $new_txt .= $new_base_url;

        $absoluteUri = $new_txt.$relativeUri;

        // Convert /dir/../ into /
        while (preg_match('/\/[^\/]+\/\.\.\//', $absoluteUri)) {
            $absoluteUri = preg_replace('/\/[^\/]+\/\.\.\//', '/', $absoluteUri);
        }

        return $absoluteUri;
    }
    
    /**
     * This function takes in a class name and returns the correct template
     * for the object.
     *
     * @param string $cname the name of the class to get the template for
     *
     * @return string Filename of the output template to use for the given class.
     */
    public static function getTemplateFilename($cname)
    {
        global $_UNL_UCBCN;
        if (isset($_UNL_UCBCN['output_template'][$cname])) {
            $cname = $_UNL_UCBCN['output_template'][$cname];
        }
        $cname = str_replace('UNL_UCBCN_', '', $cname);
        $templatefile = $cname . '.tpl.php';
        return $templatefile;
    }
    
    /**
     * Gets an MDB2 connection object and returns it.
     *
     * @return object MDB2_Driver object on success, UNL_UCBCN_Error on error.
     */
    public function getDatabaseConnection()
    {
        $mdb2 =& MDB2::connect($this->dsn);
        if (PEAR::isError($mdb2)) {
            return new UNL_UCBCN_Error($mdb2->getMessage());
        }

        if ((substr($this->dsn, 0, 5)) == 'mysql') {
            // Use UTF-8 always
            $mdb2->exec('SET NAMES "utf8";');
        }

        return $mdb2;
    }
    
    /**
     * Gets or sets the output template for a given class.
     *
     * @param string $cname        Name of the class to set/get template for.
     * @param string $templatename Name of the template to use.
     *
     * @return string
     */
    public static function outputTemplate($cname, $templatename=null)
    {
        global $_UNL_UCBCN;
        if (isset($templatename)) {
            $_UNL_UCBCN['output_template'][$cname] = $templatename;
        }
        return UNL_UCBCN::getTemplateFilename($cname);
    }
    
    /**
     * Returns the URL for the calendar system.
     *
     * @return URL to this instance.
     */
    public function getURL()
    {
        global $_UNL_UCBCN;
        if (isset($_UNL_UCBCN['frontenduri'])) {
            return $_UNL_UCBCN['frontenduri'];
        }

        return '?';
    }
    
    /**
     * This function changes the status for events in the
     * past to 'archived.'
     *
     * @param UNL_UCBCN_Calendar $cal Calendar to archive events for.
     *
     * @return num of affected rows or mdb2 error object
     */
    public function archiveEvents(UNL_UCBCN_Calendar $cal=null)
    {
        $mdb2 = UNL_UCBCN::getDatabaseConnection();
        $q    = 'UPDATE calendar_has_event,event,eventdatetime
                 SET calendar_has_event.status=\'archived\' WHERE ';
        if (isset($cal)) {
            $q .= ' calendar_has_event.calendar_id = '.$cal->id.' AND ';
        }
        $q = $q . '    calendar_has_event.status = \'posted\' AND
                   calendar_has_event.event_id = event.id AND
                   eventdatetime.event_id = event.id AND
                   eventdatetime.starttime<\''.date('Y-m-d').' 00:00:00\' AND
                   (eventdatetime.endtime IS NULL
                   OR eventdatetime.endtime<\''.date('Y-m-d').' 00:00:00\');';
        $r = $mdb2->exec($q);
        return $r;
    }
    
    /**
     * Cleans the cache.
     *
     * @param mixed $o Pass a cached object to clean it's cache, or a string id.
     *
     * @return bool true if cache was successfully cleared.
     */
    public function cleanCache($object = null)
    {
        return self::getCachingService()->clean($object);
    }
    
    static public function setCachingService(UNL_UCBCN_CachingService $cache)
    {
        self::$cache = $cache;
    }
    
    static public function getCachingService()
    {
        if (!isset(self::$cache)) {
            include_once 'UNL/UCBCN/CachingService/CacheLite.php';
            self::$cache = new UNL_UCBCN_CachingService_CacheLite();
        }
        return self::$cache;
    }
    
    /**
     * This function determines if a user can edit the details of a specific event.
     *
     * Permission relies on a couple requirements:
     *  User has 'Event Edit' rights over the calendar the event was originally
     *  created under, OR the event was 'recommended for the default calendar', and
     *  this user has permission over the default calendar.
     *
     * @param UNL_UCBCN_User  $user  User to check
     * @param UNL_UCBCN_Event $event Event to check
     *
     * @return bool true or false
     */
    public function userCanEditEvent($user, UNL_UCBCN_Event $event)
    {
        if (gettype($user)=='string') {
            $uid  = $user;
            $user = UNL_UCBCN::factory('user');
            if (!$user->get($uid)) {
                return false;
            }
        }

        // Find the originating calendar:
        $che           = UNL_UCBCN::factory('calendar_has_event');
        $che->event_id = $event->id;
        $che->whereAdd('source=\'create event form\' OR source=\'checked consider event\'');
        if ($che->find()) {
            while ($che->fetch()) {
                $c = UNL_UCBCN::factory('calendar');
                $c->get($che->calendar_id);
                if (UNL_UCBCN::userHasPermission($user, 'Event Edit', $c)) {
                    return true;
                }
            }
        }

        return false;
    }
}
?>
