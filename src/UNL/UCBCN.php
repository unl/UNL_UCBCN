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
     * Default calendar to use throughout the system.
     * @var int $default_calendar
     */
    public $default_calendar_id = 1;

    protected static $db_settings = array(
        'host'     => 'localhost',
        'user'     => 'eventcal',
        'password' => 'eventcal',
        'dbname'   => 'eventcal'
    );

    /**
     * Constructor for the UCBCN object, initializes member variables and sets up
     * connection details for the database.
     *
     * @param array $options Associative array of options to set for the class.
     */
    public function __construct($options=array())
    {
        $this->setOptions($options);
    }

    public static function setDbSettings($settings = array())
    {
        self::$db_settings = $settings + self::$db_settings;
    }

    public static function getDbSettings()
    {
        if (empty(self::$db_settings)) {
            self::setDbSettings();
        }

        return self::$db_settings;
    }

    /**
     * Connect to the database and return it
     *
     * @return mysqli
     */
    public static function getDB()
    {
        static $db = false;
        if (!$db) {
            $settings = self::getDbSettings();
            $db = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['dbname']);
            if ($db->connect_error) {
                die('Connect Error (' . $db->connect_errno . ') '
                        . $db->connect_error);
            }
            $db->set_charset('utf8');
        }
        return $db;
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
     * This function gets the count of events for the given status.
     *
     * @param UNL_UCBCN_Calendar $calendar Calendar to check.
     * @param string             $status   [pending|posted|archived]
     *
     * @return int count
     */
    public function getEventCount(UNL_UCBCN_Calendar $calendar, $status = 'posted')
    {
        $e              = UNL_UCBCN::factory('calendar_has_event');
        $e->calendar_id = $calendar->id;
        $e->status      = $status;
        return $e->find();
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

        throw new UNL_UCBCN_UnexpectedValueException('The permission you requested to check for \''
                                   . $permission_name . '\', does not exist.');
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
    public function addCalendarHasEvent(
        UNL_UCBCN_Calendar $calendar,
        UNL_UCBCN_Event $event,
        $status,
        UNL_UCBCN_User $user,
        $source=null)
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
     * @throws UNL_UCBCN_UnexpectedValueException
     *
     * @return object UNL_UCBCN_Account on success
     */
    public function getAccount(UNL_UCBCN_User $user)
    {
        $account     = UNL_UCBCN::factory('account');
        $account->id = $user->account_id;

        if ($account->find() && $account->fetch()) {
            return $account;
        }

        // No account exists!
        throw new UNL_UCBCN_UnexpectedValueException('No Account exists for the given user.');
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
    public function getCalendar(
        UNL_UCBCN_User $user,
        UNL_UCBCN_Account $account,
        $return_false = true,
        $redirecturl=null)
    {
        
        $mdb2 = $account->getDatabaseConnection();
        $res =& $mdb2->query(
            'SELECT calendar.id FROM calendar,user_has_permission
                WHERE user_has_permission.user_uid = \''.$user->uid.'\'
                    AND user_has_permission.calendar_id = calendar.id
                GROUP BY calendar.id'
                            );
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
            throw new UNL_UCBCN_RuntimeException('No permissions could be added for '
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
     * Returns the base URL of the server.
     *
     * @return string URL to the server without a URI.
     */
    function getBaseURL()
    {
       $url  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'); 
       $base = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
       $url  = $url.$_SERVER["SERVER_NAME"].$base;
       return $url;
    }
    
    /**
     * Returns the front end url of the server.
     *
     * @return string URL to the server without a URI.
     */
    function getFrontEndURL()
    {
       $url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'); 
       $url = $url.$_SERVER["SERVER_NAME"];
       return $url;
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
     * Gets an MDB2 connection object and returns it.
     *
     * @return object MDB2_Driver object on success
     */
    public function getDatabaseConnection()
    {
        $mdb2 =& MDB2::connect($this->dsn);
        if (PEAR::isError($mdb2)) {
            throw new Exception($mdb2->getMessage());
        }

        if ((substr($this->dsn, 0, 5)) == 'mysql') {
            // Use UTF-8 always
            $mdb2->exec('SET NAMES "utf8";');
        }

        return $mdb2;
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
