<?php
/**
 * This is the setup file for the UNL UCBCN Calendar System.
 * This file installs/upgrades the database and inserts the default 
 * permissions.
 */
require_once 'MDB2/Schema.php';
require_once 'UNL/UCBCN.php';

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
    
    function createDatabase($answers)
    {
    	$this->dsn = $answers['dbtype'].'://'.$answers['user'].':'.$answers['password'].'@'.$answers['dbhost'].'/'.$answers['database'];
    	$db = MDB2::connect($this->dsn);
    	if (PEAR::isError($db)) {
		    $this->_ui->outputData('Could not create database connection. "'.$db->getMessage().'"');
		    $this->noDBsetup = true;
		    return false;
		}
		$a = self::file_str_replace(	'<name>eventcal</name>',
										'<name>'.$answers['database'].'</name>',
										'@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.xml');
		if ($a != true) {
			$this->noDBsetup = true;
			return $a;
		}
    	$manager =& MDB2_Schema::factory($db);
		if (PEAR::isError($manager)) {
		   $this->_ui->outputData($manager->getMessage() . ' ' . $manager->getUserInfo());
		   $this->noDBsetup = true;
		   return false;
		} else {
			// Temporary fix for me... remove before distributing.
			/**
			 * @todo copy the MDB xml file to a filename corresponding to the database name. IE: copy(UNL_UCBCN_db.xml, UNL_UCBCN_$answers['db']);
			 * 			then perform comparison install & upgrade on that file to allow installation to multiple databases. 
			 */
			unlink('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.old');
			$operation = $manager->updateDatabase('@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.xml'
                , '@DATA_DIR@/UNL_UCBCN/UNL_UCBCN_db.old');
            if (PEAR::isError($operation)) {
                $this->_ui->outputData($operation->getMessage() . ' ' . $operation->getUserInfo());
                $this->noDBsetup = true;
                return false;
            } else {
				$this->_ui->outputData('Successfully connected and created '.$this->dsn."\n");
            	return true;
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
					$this->_ui->outputData($file);
					return true;
				} else {
					$this->_ui->outputData('Could not update ' . $file);
					return false;
				}
    		} else {
    			$this->_ui->outputData($file.' does not exist!');
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
							'Calendar Format',
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
}
?>