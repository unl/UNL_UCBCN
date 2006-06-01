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
