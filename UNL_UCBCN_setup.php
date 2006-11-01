<?php
/**
 * This is the setup file for the UNL UCBCN Calendar System.
 * This file installs/upgrades the database and inserts the default 
 * permissions.
 * 
 * @package		UNL_UCBCN
 * @author		Brett Bieber
 */

/**
 * Require MDB2_Schema for database interactions, and the UNL_UCBCN class
 * for backend interactions.
 */
require_once 'MDB2/Schema.php';
require_once 'UNL/UCBCN.php';

/**
 * Class used by the PEAR installer which is executed after install to do
 * post installation tasks such as database creation/updates as well as
 * replacements and configuration.
 *
 * @package UNL_UCBCN
 */
class UNL_UCBCN_setup_postinstall
{
	var $createDB;
	var $databaseExists;
    var $noDBsetup;
	var $dsn;
	
	function init(&$config, &$pkg, $lastversion)
    {
        $this->_config = &$config;
        $this->_registry = &$config->getRegistry();
        $this->_ui = &PEAR_Frontend::singleton();
        $this->_pkg = &$pkg;
        $this->lastversion = $lastversion;
        $this->databaseExists = false;
        return true;
    }
    
    function postProcessPrompts($prompts, $section)
    {
        switch ($section) {
            case 'databaseSetup' :
                
            break;
        }
        return $prompts;
    }
    
    function run($answers, $phase)
    {
        switch ($phase) {
        	case 'questionCreate':
        		$this->createDB		= ($answers['createdb']=='yes')?true:false;
    			return $this->createDB;
            case 'databaseSetup' :
            	if ($this->createDB) {
               		$r = $this->createDatabase($answers);
               		return ($r && $this->setupPermissions($answers));
            	} else {
            		return true;
            	}
            case '_undoOnError' :
                // answers contains paramgroups that succeeded in reverse order
                foreach ($answers as $group) {
                    switch ($group) {
                        case 'makedatabase' :
                        break;
                        case 'databaseSetup' :
                            if ($this->lastversion || $this->databaseExists || $this->noDBsetup) {
                                // don't uninstall the database if it had already existed
                                break;
                            }
                            /* REMOVE THE DATABASE ON FAILURE! */
                        break;
                    }
                }
            break;
        }
    }
    /**
     * Creates or updates the calendar system database.
     *
     * @param array $answers Associative array of responses to the questsions asked.
     * @return bool true or false on success or error.
     */
    function createDatabase($answers)
    {
    	$this->dsn = $answers['dbtype'].'://'.$answers['user'].':'.$answers['password'].'@'.$answers['dbhost'].'/'.$answers['database'];
    	$db = MDB2::connect($this->dsn);
    	if (PEAR::isError($db)) {
		    $this->outputData('Could not create database connection. "'.$db->getMessage().'"');
		    $this->noDBsetup = true;
		    return false;
		}
		
		if ($answers['database'] != 'eventcal') {
			$a = self::file_str_replace(	'<name>eventcal</name>',
											'<name>'.$answers['database'].'</name>',
											'@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.xml');
			if ($a != true) {
				$this->noDBsetup = true;
				return $a;
			}
            $ds = DIRECTORY_SEPARATOR;
            $this->outputData('Copying DB_DataObject config file to "@DATA_DIR@' .
                $ds.'UNL_UCBCN'.$ds.'UCBCN'.$ds.$answers['database'].'.ini"'."\n");
            copy('@DATA_DIR@' . $ds . 'UNL_UCBCN' . $ds . 'UCBCN' . $ds .
                    'eventcal.ini',
                '@DATA_DIR@' . $ds . 'UNL_UCBCN' . $ds . 'UCBCN' . $ds .
                    $answers['database'] . '.ini');
            copy('@DATA_DIR@' . $ds . 'UNL_UCBCN' . $ds . 'UCBCN' . $ds .
                    'eventcal.links.ini',
                '@DATA_DIR@' . $ds . 'UNL_UCBCN' . $ds . 'UCBCN' . $ds .
                    $answers['database'] . '.links.ini');
        }
    	$manager =& MDB2_Schema::factory($db);
		if (PEAR::isError($manager)) {
		   $this->outputData($manager->getMessage() . ' ' . $manager->getUserInfo());
		   $this->noDBsetup = true;
		   return false;
		} else {
			// Check if there is an old install with no database name.
			if (!file_exists('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db_'.$answers['database'].'.old')
			    && file_exists('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.old')) {
			    // Copy the old xml file to a correctly named file.
			    copy('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.old','@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db_'.$answers['database'].'.old');
		    }
		    $previous_definition = $manager->parseDatabaseDefinitionFile('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db_'.$answers['database'].'.old');
		    $current_definition = $manager->parseDatabaseDefinitionFile('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.xml');
		    $changes = $manager->compareDefinitions($current_definition, $previous_definition);
		    if (($manager->verifyAlterDatabase($changes))) {
				$operation = $manager->updateDatabase('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.xml'
	                , '@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db_'.$answers['database'].'.old');
	            if (PEAR::isError($operation)) {
	                $this->outputData($operation->getMessage() . ' ' . $operation->getDebugInfo());
	                $this->noDBsetup = true;
	                return false;
	            } else {
					$this->outputData('Successfully connected and created '.$this->dsn."\n");
	            	return true;
	            }
		    } else {
		        $this->outputData('Sorry, the changes cannot be automatically applied by MDB2_Schema'."\n");
		        return false;
		    }
		}
    }
    
    function file_str_replace($search,$replace,$file)
    {
    	$a = true;
    	if (is_array($file)) {
    		foreach ($file as $f) {
    			$a = self::file_str_replace($search,$replace,$f);
    			if ($a != true) {
    				return $a;
    			}
    		}
    	} else {
    		if (file_exists($file)) {
				$contents = file_get_contents($file);
				$contents = str_replace($search,$replace,$contents);
	
				$fp = fopen($file, 'w');
				$a = fwrite($fp, $contents, strlen($contents));
				fclose($fp);
				if ($a) {
					$this->outputData('Replacements made in '.$file."\n");
					return true;
				} else {
					$this->outputData('Could not update '.$file."\n");
					return false;
				}
    		} else {
    			$this->outputData($file.' does not exist!');
    		}
    	}
    }
    
    /**
     * This function calls the backend and inserts the default permissions for the system.
     */
    function setupPermissions($answers)
    {
		$backend = new UNL_UCBCN(array('dsn'=>$this->dsn));
		$permissions = array(
							'Event Create',
							'Event Delete',
							'Event Post',
							'Event Send Event to Pending Queue',
							'Event Edit',
							'Event Recommend',
							'Event Remove from Pending',
							'Event Remove from Posted',
							'Event Remove from System ',
							'Event View Queue',
							'Event Export',
							'Event Upload',
							'Calendar Add User',
							'Calendar Add Subscription',
							'Calendar Delete Subscription',
							'Calendar Delete User',
							'Calendar Edit Subscription',
							'Calendar Change User Permissions',
							'Calendar Format',
							'Calendar Edit',
							'Calendar Change Format',
							'Calendar Delete');
		foreach ($permissions as $p_type) {
			$p = $backend->factory('permission');
			$p->name = $p_type;
			if (!$p->find()) {
				$p->name = $p_type;
				$p->description = $p_type;
				$p->insert();
			} else {
				echo "Sorry, $p_type already exists.\n";
			}
		}
		return true;
    }
    
    /**
     * takes in a string and sends it to the client.
     */
    function outputData($msg)
    {
    	if (isset($this->_ui)) {
    		$this->_ui->outputData($msg);
    	} else {
    		echo $msg;
    	}
    }
}
?>