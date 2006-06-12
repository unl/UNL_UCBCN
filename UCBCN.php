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
		);
	}
	
	/**
	 * This function sets parameters for this class.
	 * 
	 * @param array $options an associative array of options to set.
	 */
	function setOptions($options)
	{
		foreach ($options as $option=>$val) {
			if (property_exists($this,$option)) {
				switch($option) {
					case 'template':
						/* 
						 * Set a global variable for the template type so that static functions know
						 * what template to render pages in. 
						 */ 
						$GLOBALS['template'] = $val;
					break;		
				}
				$this->$option = $val;
			} else {
				echo 'Warning: Trying to set unkown option ['.$option.']';
			}
		}
	}
	
	function getEventList($date='')
	{
		$list = '';
		$events = DB_DataObject::factory('event');
		$events->eventDate = $date;
		if ($events->find()) {
			while ($events->fetch()) {
				$list .= $events->strTitle;
			}
		} else {
			$list .= 'No events';
		}
		return $list;
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
	 * @param string uid unique id of the user to create
	 * @param string optional unique id of the user who created this user.
	 */
	function createUser($uid,$uidcreated=NULL)
	{
		$values = array(
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
	 * @param object UNL_UCBCN_Account
	 * @return bool true or false
	 */
	 function userHasPermission($user,$permission,$account)
	 {
	 	$permission				= $this->factory('permission');
	 	$permission->name		= $permission;
	 	$user_has_permission	= $this->factory('user_has_permission');
	 	$user_has_permission->linkAdd($permission);
	 	$user_has_permission->linkAdd($account);
	 	$user_has_permission->user_uid = $user->uid;
	 	return $user_has_permission->find();
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
	function displayRegion($content)
	{
		require_once 'Savant3.php';
		$savant = new Savant3();
		if (is_object($content)) {
			// Set up the template to display.
			foreach (get_object_vars($content) as $key=>$var) {
				$savant->$key = $var;
			}
			$templatefile = UNL_UCBCN::getTemplateFilename(get_class($content));
			if (file_exists($templatefile)) {
				$savant->display($templatefile);
			} else {
				echo 'Sorry, '.$templatefile.' was not found.';
			}
		} elseif(is_array($content)) {
			foreach($content as $contentregion) {
				UNL_UCBCN::displayRegion($contentregion);
			}
		} else {
			echo $content;
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
				'datelastupdated'	=> date('Y-m-d H:i:s'),
				'uidlastupdated'	=> 'system',
				'uidcreated'		=> 'system');
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
						'uid_created'	=> $user->uid,
						'date_last_updated'	=> date('Y-m-d H:i:s'),
						'uid_last_updated'	=> $user->uid,
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
			return $this->createUser($uid,$uid);
		}
	}
	
	/**
	 * Gets the account record(s) that the given user has permission to.
	 * If an account does not exist, one is created and returned. Optionally
	 * the user can be redirected on creation of a new account.
	 * 
	 * @param string $uid User id to get an account from.
	 * @param string $redirecturl URL to redirect on new account creation.
	 */
	function getAccount($uid,$redirecturl = NULL)
	{
		$account = $this->factory('account');
		$user_has_permission = $this->factory('user_has_permission');
		$user_has_permission->user_uid = $uid;
		$account->linkAdd($user_has_permission);
		if ($account->find() && $account->fetch()) {
			return $account;
		} else {
			$user = $this->getUser($uid);
			// No account exists!
			$values = array(
						'name'				=> ucfirst($user->uid).'\'s Event Publisher!',
						'uidcreated'		=> $user->uid,
						'uidlastupdated'	=> $user->uid);
			$account = $this->createAccount($values);
			$permissions = $this->factory('permission');
			$permissions->whereAdd('name LIKE "Event%"');
			if ($permissions->find()) {
				while ($permissions->fetch()) {
					$this->grantPermission($uid,$account->id,$permissions->id);
				}
			}
			if (isset($redirecturl)) {
				// Account has been created, but has no details, send the user to the edit account page?
				$this->localRedirect($redirecturl);
			} else {
				return $account;
			}
		}
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
		$url = self::getURL($url, $keepProtocol);
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
	function getURL($url)
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
		$cname = str_replace('UNL_UCBCN_','',$cname);
		$templatefile = 'templates' . DIRECTORY_SEPARATOR .
						$GLOBALS['template'] . DIRECTORY_SEPARATOR .
						$cname . '.php';
		return $templatefile;
	}
}
?>
