<?php
/**
 * Make package file for the UNL_UCBCN package.
 * 
 * @package UNL_UCBCN
 * @author Brett Bieber
 */

ini_set('display_errors',true);

/**
 * Require the PEAR_PackageFileManager2 classes, and other
 * necessary classes for package.xml file creation.
 */
require_once 'PEAR/PackageFileManager2.php';
require_once 'PEAR/PackageFileManager/File.php';
require_once 'PEAR/Task/Postinstallscript/rw.php';
require_once 'PEAR/Config.php';
require_once 'PEAR/Frontend.php';

/**
 * @var PEAR_PackageFileManager
 */
PEAR::setErrorHandling(PEAR_ERROR_DIE);
chdir(dirname(__FILE__));
$pfm = PEAR_PackageFileManager2::importOptions('package.xml', array(
//$pfm = new PEAR_PackageFileManager2();
//$pfm->setOptions(array(
	'packagedirectory' => dirname(__FILE__),
	'baseinstalldir' => 'UNL',
	'filelistgenerator' => 'cvs',
	'ignore' => array(	'package.xml',
						'.project',
						'*.tgz',
						'makepackage.php',
						'*CVS/*',
						'*.sh',
						'*.svg',
						'.cache',
						'dataobject.ini',
						'DBDataObjects',
						'insert_sample_data.php',
						'install.sh',
						'tests',
						'tests/*'),
	'simpleoutput' => true,
	'roles'=>array('php'=>'php'	),
	'exceptions'=>array()
));
$pfm->setPackage('UNL_UCBCN');
$pfm->setPackageType('php'); // this is a PEAR-style php script package
$pfm->setSummary('This package provides the database interactions for a UC Berkeley Calendar system.');
$pfm->setDescription('This package creates and upgrades a relational database used to store event publishing details
					formatted using the University of California Berkeley Calendar Network schema. The backend provides
					basic functions for an event management frontend, as well as a public frontend.');
$pfm->setChannel('pear.unl.edu');
$pfm->setAPIStability('beta');
$pfm->setReleaseStability('beta');
$pfm->setAPIVersion('0.5.0');
$pfm->setReleaseVersion('0.5.3');
$pfm->setNotes('
* Coding standards... change license.
* Add getLocation function.');

//$pfm->addMaintainer('lead','saltybeagle','Brett Bieber','brett.bieber@gmail.com');
$pfm->setLicense('BSD License', 'http://www.opensource.org/licenses/bsd-license.php');
$pfm->clearDeps();
$pfm->setPhpDep('5.0.0');
$pfm->setPearinstallerDep('1.4.3');
$pfm->addPackageDepWithChannel('required', 'Cache_Lite', 'pear.php.net', '1.0');
$pfm->addPackageDepWithChannel('required', 'DB_DataObject', 'pear.php.net', '0.8');
$pfm->addPackageDepWithChannel('required', 'Savant3', 'savant.pearified.com', '3.0.0');
$pfm->addPackageDepWithChannel('required', 'NET_URL', 'pear.php.net', '1.0');
$pfm->addPackageDepWithChannel('required', 'MDB2_Schema', 'pear.php.net', '0.5.0');
foreach (array('UCBCN.php','dataobject.ini','UNL_UCBCN_setup.php','UNL_UCBCN_db.xml') as $file) {
	$pfm->addReplacement($file, 'pear-config', '@PHP_BIN@', 'php_bin');
	$pfm->addReplacement($file, 'pear-config', '@PHP_DIR@', 'php_dir');
	$pfm->addReplacement($file, 'pear-config', '@DATA_DIR@', 'data_dir');
	$pfm->addReplacement($file, 'pear-config', '@DOC_DIR@', 'doc_dir');
}

$config = PEAR_Config::singleton();
$log = PEAR_Frontend::singleton();
$task = new PEAR_Task_Postinstallscript_rw($pfm, $config, $log,
    array('name' => 'UNL_UCBCN_setup.php', 'role' => 'php'));
$task->addParamGroup('questionCreate', array(
	$task->getParam('createdb',	'Create/Upgrade database for UNL_UCBCN?', 'string', 'yes'),
	));
$task->addParamGroup('databaseSetup', array(
	$task->getParam('dbtype',		'Database/connection type', 'string', 'mysqli'),
    $task->getParam('database',	'Calendar database', 'string', 'eventcal'),
    $task->getParam('user',		'Username (must have create permision)', 'string', 'eventcal'),
    $task->getParam('password',	'Mysql password', 'string', 'eventcal'),
    $task->getParam('dbhost',		'Database Host', 'string', 'localhost')
    ));

$pfm->addPostinstallTask($task, 'UNL_UCBCN_setup.php');
$pfm->generateContents();
if (isset($_SERVER['argv']) && $_SERVER['argv'][1] == 'make') {
    $pfm->writePackageFile();
} else {
    $pfm->debugPackageFile();
}
?>