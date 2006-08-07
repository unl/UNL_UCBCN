<?php
/**
 * This is a skeleton PEAR package attempt for the UC Berkeley Calendar Schema.
 * The class facilitates interaction between child objects and the database. It also
 * contains static functions useful throughout various frontends including conversion
 * between various formats:
 * 		Database <--> iCalendar
 * 		Database <--> hCalendar
 * 		Database <--> xml conforming to http://groups.sims.berkeley.edu/EventCalendar/UCBEvents.xsd
 * 
 * It also provides help to frontends that want to display information through a template system.
 * 
 * @author bbieber
 * @package UNL_UCBCN
 * 
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN/Error.php';
require_once 'MDB2.php';

class UNL_UCBCN
{
	/** The template chosen to display in. default */
	var $template;
	/** The filesystem path to the templates. */
	var $template_path;
	/** A string containing connection details in the format dbtype://user:pass@www.example.com:port/database */
	var $dsn;
	
	/**
	 * Constructor for the UCBCN object, initializes member variables and sets up 
	 * connection details for the database.
	 */
	function __construct($options=array(	'dsn'=>'@DSN@',
											'template_path'=>''))
	{
		$this->setOptions($options);
		$this->setupDBConn();
	}
	
	/**
	 * This function initializes the information used by the database
	 * connections.
	 */
	function setupDBConn()
	{
		$dboptions = &PEAR::getStaticProperty('DB_DataObject','options');
		$dboptions = array(
		    'database'         => $this->dsn,
		    'schema_location'  => '@DATA_DIR@/UNL_UCBCN/UCBCN',
		    'class_location'   => '@PHP_DIR@/UNL/UCBCN',
		    'require_prefix'   => '@PHP_DIR@/UNL/UCBCN',
		    'class_prefix'     => 'UNL_UCBCN_',
		    'db_driver'			=> 'MDB'
		);
	}
	
	/**
	 * This function sets parameters for this class.
	 * 
	 * @param array $options an associative array of options to set.
	 */
	function setOptions($options)
	{
		global $_UNL_UCBCN;
		foreach ($options as $option=>$val) {
			if (property_exists($this,$option)) {
				switch($option) {
					case 'template':
					case 'template_path':
					case 'frontenduri':
					case 'manageruri':
					case 'uri':
					case 'defaultcalendarid':
					case 'uriformat':
						/* 
						 * Set a global variable for this value, it is used in static functions. 
						 */ 
						$_UNL_UCBCN[$option] = $val;
					break;
				}
				$this->$option = $val;
			} else {
				echo 'Warning: Trying to set unkown option ['.$option.']';
			}
		}
	}
	
	/**
	 * This function gets the count of events for the given status.
	 * 
	 * @param object UNL_UCBCN_Calendar object
	 * @param string status [pending|posted|archived]
	 * @return int count
	 */
	function getEventCount($calendar,$status='posted')
	{
		$e = $this->factory('calendar_has_event');
		$e->calendar_id = $calendar->id;
		$e->status = $status;
		return $e->find();
	}
	
	/**
	 * This function allows extended classes etc to get a DB DataObject 
	 * for the event table they need access to.
	 * 
	 * @param string $table The name of the table in the database to receive a DataObject for.
	 */
	function factory($table)
	{
		return DB_DataObject::factory($table);
	}
	
	/**
	 * creates a new user record and returns it.
	 * @param object account UNL_UCBCN_Account object
	 * @param string uid unique id of the user to create
	 * @param string optional unique id of the user who created this user.
	 */
	function createUser($account,$uid,$uidcreated=NULL)
	{
		$values = array(
			'account_id'		=> $account->id,
			'uid' 				=> $uid,
			'datecreated'		=> date('Y-m-d H:i:s'),
			'uidcreated'		=> $uidcreated,
			'datelastupdated' 	=> date('Y-m-d H:i:s'),
			'uidlastupdated'	=> $uidcreated);
		return $this->dbInsert('user',$values);
	}
	
	/**
	 * This function is a general insert function,
	 * given the table name and an assoc array of values, 
	 * it will return the inserted record.
	 * 
	 * @param string $table Name of the table
	 * @param array $values assoc array of values to insert.
	 * @return object on success, failed return value on failure.
	 */
	function dbInsert($table,$values)
	{
		$rec = $this->factory($table);
		$vars = get_object_vars($rec);
		foreach ($values as $var=>$value) {
			if (in_array($var,$vars)) {
				$rec->$var = $value;
			}
		}
		$result = $rec->insert();
		if (!$result) {
			return $result;
		} else {
			return $rec;
		}
	}
	
	/**
	 * Checks if a user has a given permission over the account.
	 * 
	 * @param object UNL_UCBCN_User
	 * @param string permission
	 * @param object UNL_UCBCN_Calendar
	 * @return bool true or false
	 */
	 function userHasPermission($user,$permission_name,$calendar)
	 {
	 	$permission				= $this->factory('permission');
	 	$permission->name		= $permission_name;
	 	if ($permission->find() && $permission->fetch()) {
		 	$user_has_permission	= $this->factory('user_has_permission');
		 	$user_has_permission->permission_id = $permission->id;
		 	$user_has_permission->calendar_id = $calendar->id;
		 	$user_has_permission->user_uid = $user->uid;
		 	return $user_has_permission->find();
	 	} else {
	 		return new UNL_UCBCN_Error('The permission you requested to check for \''.$permission.'\', does not exist.');
	 	}
	 }
	
	function showError($description)
	{
		$this->displayRegion($description);
	}
	
	/**
	 * The heart of the template/display portions of this system.
	 * A simple function which renders the given content using a savant
	 * formatted template based on the type of the object.
	 * IE: 	strings and ints get echoed
	 * 		objects use a corresponding savant template,
	 * 		arrays get rendered one by one
	 */
	function displayRegion($content,$cachekey=NULL)
	{
		global $_UNL_UCBCN;
		if (is_object($content)) {
			if (method_exists($content,'getCacheKey') && method_exists($content,'run')) {
				require_once 'Cache/Lite.php';
				$cache = new Cache_Lite();
				if ($data = $cache->get($content->getCacheKey())) {
					$content->preRun(true);
				} else {
					ob_start();
					$content->preRun(false);
					$content->run();
					UNL_UCBCN::sendObjectOutput($content);
					$data = ob_get_contents();
					$cache->save($data);
					ob_end_clean();
				}
				echo $data;
			} else {
				// Set up the template to display.
				UNL_UCBCN::sendObjectOutput($content);
			}
		} elseif(is_array($content)) {
			foreach($content as $contentregion) {
				UNL_UCBCN::displayRegion($contentregion);
			}
		} else {
			echo $content;
		}
	}
	
	function sendObjectOutput($content)
	{
		require_once 'Savant3.php';
		$savant = new Savant3();
		foreach (get_object_vars($content) as $key=>$var) {
			$savant->$key = $var;
		}
		$templatefile = UNL_UCBCN::getTemplateFilename(get_class($content));
		if (file_exists($templatefile)) {
			$savant->display($templatefile);
		} else {
			echo 'Sorry, '.$templatefile.' was not found.';
		}
	}
	
	/**
	 * This function adds the given permission for the user.
	 * 
	 * @param string $uid Username to add permission for.
	 * @param int $account_id ID of the account to add permission for.
	 * @param int $permission_id ID of the permission you wish to add for the person.
	 */
	function grantPermission($uid,$calendar_id,$permission_id)
	{
		$values = array(
						'calendar_id'	=> $calendar_id,
						'user_uid'		=> $uid,
						'permission_id'=>	$permission_id
						);
		return $this->dbInsert('user_has_permission',$values);
	}
	
	/**
	 * This function creates a calendar account.
	 * 
	 * @param array $values assoc array of field values for the account.
	 */
	function createAccount($values = array())
	{
		$defaults = array(
				'datecreated'		=> date('Y-m-d H:i:s'),
				'datelastupdated'	=> date('Y-m-d H:i:s'));
		$values = array_merge($defaults,$values);
		return $this->dbInsert('account',$values);
	}
	
	/**
	 * Adds an event to a calendar.
	 * 
	 * @param object calendar, UNL_UCBCN_Calendar object.
	 * @param object UNL_UCBCN_Event object.
	 * @param sring status=[pending|posted|archived]
	 * @param object UNL_UCBCN_User object
	 * 
	 * @return object UNL_UCBCN_Account_has_event
	 */
	function addCalendarHasEvent($calendar,$event,$status,$user)
	{
		$values = array(
						'calendar_id'	=> $calendar->id,
						'event_id'		=> $event->id,
						'uidcreated'	=> $user->uid,
						'datecreated'	=> date('Y-m-d H:i:s'),
						'datelastupdated'	=> date('Y-m-d H:i:s'),
						'uidlastupdated'	=> $user->uid,
						'status'		=> $status);
		return $this->dbInsert('calendar_has_event',$values);
	}
	
	/**
	 * This function returns a object for the user with
	 * the given uid.
	 * If a record does not exist, one is inserted then returned.
	 * 
	 * @param string $uid The unique user identifier for the user you wish to get (username/ldap uid).
	 * @return object UNL_UCBCN_User
	 */
	function getUser($uid)
	{
		$user = $this->factory('user');
		$user->uid = $uid;
		if ($user->find()) {
			$user->fetch();
			return $user;
		} else {
			if (!isset($this->account)) {
				// No account is currently set, create one for this user.
				$values = array(
					'name'				=> ucfirst($user->uid).' Calendar Manager');
				$account = $this->createAccount($values);
			} else {
				$account = $this->account;
			}
			if (!isset($this->user)) {
				// No current user... this user has created his own user entry.
				$created_by = $uid;
			} else {
				// Another user has created this user.
				$created_by = $this->user->uid;
			}
			return $this->createUser($account,$uid,$created_by);
		}
	}
	
	/**
	 * Gets the account record(s) for the user
	 * 
	 * @param object $user UNL_UCBCN_User object
	 */
	function getAccount($user)
	{
		$account = $this->factory('account');
		$account->id = $user->account_id;
		if ($account->find() && $account->fetch()) {
			return $account;
		} else {
			// No account exists!
			return new UNL_UCBCN_Error('No Account exists for the given user.');
		}
	}
	
	/**
	 * Gets the calendar(s) for the given account that the given user has permission to.
	 * Optionally the user can be redirected on creation of a new calendar.
	 * 
	 * @param object $user UNL_UCBCN_User object.
	 * @param object $account UNL_UCBCN_Account object
	 * @param bool $return_false If true, will return false if no account exists, if false, it will invoke createCalendar.
	 * @param string redirect_url A url to redirect on creation of a new record. If set the user will be redirected, otherwise the account will be returned.
	 */
	function getCalendar($user,$account,$return_false = true, $redirecturl=NULL)
	{
		$user_has_permission = $this->factory('user_has_permission');
		$user_has_permission->user_uid = $user->uid;
		$calendar = $this->factory('calendar');
		$calendar->joinAdd($user_has_permission);
		if ($calendar->find() && $calendar->fetch()) {
			return $calendar;
		} else {
			// No Calendar exists for the given account...
			if ($return_false == true) {
				return false;
			} else {
				// Create a new calendar and account and return the calendar.
				$values = array(
							'name'				=> ucfirst($user->uid).'\'s Event Publisher!',
							'shortname'			=> $user->uid,
							'uidcreated'		=> $user->uid,
							'uidlastupdated'	=> $user->uid,
							'account_id'		=> $account->id);
				$calendar = $this->createCalendar($values);
				$permissions = $this->factory('permission');
				//$permissions->whereAdd('name LIKE "Event%"');
				// grant all permissions to this new user for this new calendar.
				if ($permissions->find()) {
					while ($permissions->fetch()) {
						$this->grantPermission($user->uid,$calendar->id,$permissions->id);
					}
				} else {
					// Setup default permissions...?
					return new UNL_UCBCN_Error('No permissions could be added for the new user! Permissions need to be added to the permission table.');
				}
				if (isset($redirecturl)) {
					$this->localRedirect($redirecturl);
				} else {
					return $calendar;	
				}
			}
		}
	}
	
	/**
	 * This function creates a calendar for an account.
	 * 
	 * @param array $values assoc array of field values for the calendar.
	 */
	function createCalendar($values = array())
	{
		$defaults = array(
				'datecreated'		=> date('Y-m-d H:i:s'),
				'datelastupdated'	=> date('Y-m-d H:i:s'),
				'uidlastupdated'	=> 'system',
				'uidcreated'		=> 'system');
		$values = array_merge($defaults,$values);
		return $this->dbInsert('calendar',$values);
	}
	
	/**
	 * Redirects to the given full or partial URL.
	 * will turn the given url into an absolute url
	 * using the above getURL() function. This function
	 * does not return.
	 *
	 * @param string $url Full/partial url to redirect to
	 * @param  bool  $keepProtocol Whether to keep the current protocol or to force HTTP
	 */
	function localRedirect($url, $keepProtocol = true)
	{
		$url = self::getAbsoluteURL($url, $keepProtocol);
		if  ($keepProtocol == false) {
			$url = preg_replace("/^https/", "http", $url);
		}
		header('Location: ' . $url);
		exit;
	}
	
	/**
	 * Returns an absolute URL using Net_URL
	 *
	 * @param  string $url All/part of a url
	 * @return string      Full url
	 */
	function getAbsoluteURL($url)
	{
		include_once 'Net/URL.php';
		$obj = new Net_URL($url);
		return $obj->getURL();
	}
	
	/**
	 * This function takes in a class name and returns the correct template
	 * for the object.
	 * @param string $class the name of the class to get the 
	 */
	function getTemplateFilename($cname)
	{
		global $_UNL_UCBCN;
		if (isset($_UNL_UCBCN['output_template'][$cname])) {
			$cname = $_UNL_UCBCN['output_template'][$cname];
		}
		$cname = str_replace('UNL_UCBCN_','',$cname);
		if (!empty($_UNL_UCBCN['template_path'])) {
			$templatefile = $_UNL_UCBCN['template_path'] . DIRECTORY_SEPARATOR . $cname . '.tpl.php';
		} else {
			$templatefile = 'templates' . DIRECTORY_SEPARATOR .
							$_UNL_UCBCN['template'] . DIRECTORY_SEPARATOR .
							$cname . '.tpl.php';
		}
		return $templatefile;
	}
	
	/**
	 * Gets an MDB2 connection object and returns it.
	 */
	function getDatabaseConnection()
	{
		$mdb2 =& MDB2::connect($this->dsn);
		if (PEAR::isError($mdb2)) {
		    return new UNL_UCBCN_Error($mdb2->getMessage());
		} else {
			return $mdb2;
		}
	}
	
	/**
	 * Gets or sets the output template for a given class.
	 */
	function outputTemplate($cname,$templatename=NULL)
	{
		global $_UNL_UCBCN;
		if (isset($templatename)) {
			$_UNL_UCBCN['output_template'][$cname] = $templatename;
		}
		return UNL_UCBCN::getTemplateFilename($cname);
	}
	
	/**
	 * Returns the URL for the calendar system.
	 */
	function getURL()
	{
		global $_UNL_UCBCN;
		if (isset($_UNL_UCBCN['frontenduri'])) {
			return $_UNL_UCBCN['frontenduri'];
		} else {
			return '?';
		}
	}
}
?>
