<?php
/**
 * Basic test to determine if the schema can be used by MDB2_Schema for database creation.
 * This is based on an example file from MDB2_Schema.
 * 
 * @package  UNL_UCBCN
 */

/**
 * Require the PHPUnit framework.
 */
require_once 'PHPUnit/Framework/TestCase.php';
ini_set('display_errors',true);
/**
 * Include MDB2_Schema for the test case.
 */
require_once 'MDB2/Schema.php';
require_once 'MDB2/Driver/sqlite.php';

/**
 * This class can be used by PHPUnit to test the UNL_UCBCN database schema.
 * 
 * @package UNL_UCBCN
 *
 */
class UNL_UCBCN_SchemaTest extends PHPUnit_Framework_TestCase
{
    //contains the dsn of the database we are testing
    var $dsn;
    //contains the options that should be used during testing
    var $options;
    //contains the name of the database we are testing
    var $database;
    //contains the MDB2_Schema object of the db once we have connected
    var $schema;
    //contains the name of the driver_test schema
    var $driver_input_file = '../UNL_UCBCN_db.xml';
    //contains the name of the extension to use for backup schemas
    var $backup_extension = '.before';

    function MDB2_Schema_Test($name) {
        $this->PHPUnit_TestCase($name);
    }

    function setUp() {
        chdir(dirname(__FILE__));
        $this->dsn = 'sqlite:///eventcal';
        $this->options = array();
        $this->database = 'eventcal';
        $backup_file = $this->driver_input_file.$this->backup_extension;
        if (file_exists($backup_file)) {
            unlink($backup_file);
        }
        
        $this->schema =& MDB2_Schema::factory($this->dsn, $this->options);
        if (PEAR::isError($this->schema)) {
            $this->assertTrue(false, 'Could not connect to manager in setUp:'.$this->schema->getUserInfo());
            exit;
        }
        $this->schema->db->setOption('debug', true);
    }

    function tearDown() {
        unset($this->dsn);
        if (!PEAR::isError($this->schema)) {
            $this->schema->disconnect();
        }
        unset($this->schema);
    }

    function methodExists(&$class, $name) {
        if (is_object($class)
            && array_key_exists(strtolower($name), array_change_key_case(array_flip(get_class_methods($class)), CASE_LOWER))
        ) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name.' not implemented in '.get_class($class));
        return false;
    }

    function testCreateDatabase() {
        // Remove the old database file.
        if (file_exists('eventcal')) {
            unlink('eventcal');
        }
        $this->schema->db->expectError('*');
        if (!$this->methodExists($this->schema, 'updateDatabase')) {
            return;
        }
        $result = $this->schema->updateDatabase(
            $this->driver_input_file,
            false,
            array('create' => '1', 'name' => $this->database)
        );
        if (PEAR::isError($result)) {
            $result = $this->schema->updateDatabase(
                $this->driver_input_file,
                false,
                array('create' => '0', 'name' => $this->database)
            );
        }
        $this->assertFalse(PEAR::isError($result), 'Error creating database');
    }

    function testUpdateDatabase() {
        if (!$this->methodExists($this->schema, 'updateDatabase')) {
            return;
        }
        $backup_file = $this->driver_input_file.$this->backup_extension;
        if (!file_exists($backup_file)) {
            copy($this->driver_input_file, $backup_file);
        }
        $result = $this->schema->updateDatabase(
            $this->driver_input_file,
            $backup_file,
            array('create' =>'0', 'name' =>$this->database)
        );
        $this->assertFalse(PEAR::isError($result), 'Error updating database');
    }
}
// Call UNL_UCBCN_SchemaTest::main() if file is executed directly.


?>