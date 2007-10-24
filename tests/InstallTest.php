<?php
/**
 * This file is used to test the UNL_UCBCN installation.
 * 
 * @package UNL_UCBCN
 * @author Brett Bieber
 */

/**
 * Require the PHPUnit framework and UNL_UCBCN backend system.
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'UNL/UCBCN.php';

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

/**
 * InstallTest class.
 *
 * @package UNL_UCBCN
 */
class UNL_UCBCN_InstallTest extends PHPUnit_Framework_TestCase
{
    /**
     * Database connection string for the sqlite test database.
     *
     * @var string
     */
    public $dsn = 'mysqli://eventcal:eventcal@localhost/eventcal';
    
    public function setUp()
    {
        chdir(dirname(__FILE__));
    }
    
    /**
     * Test the installation script
     * 
     * @see UNL_UCBCN_setup_postinstall
     *
     */
    public function testBackendInstallScript()
    {
		require_once 'PEAR.php';
		require_once 'UNL/UNL_UCBCN_setup.php';
		$installer = new UNL_UCBCN_setup_postinstall();
		$res = $installer->createDatabase(array('dbtype'=>'mysqli','user'=>'eventcal','password'=>'eventcal','dbhost'=>'localhost','database'=>'eventcal'));
		$this->assertFalse(PEAR::isError($res));
		flush();
		ob_start();
		$installer->setupPermissions(array());
		ob_clean();
    }
    
    /**
     * Test that permissions were created successfully.
     *
     */
    public function testPermissions()
    {
        $b = new UNL_UCBCN(array('dsn'=>$this->dsn));
        $p = $b->factory('permission');
        $this->assertEquals(get_class($p),'UNL_UCBCN_Permission');
        $this->assertNotEquals(0, $p->find());
    }
    
    /**
     * Test that the backend can get a simple user.
     *
     */
    public function testBackendInfo()
    {
        $b = new UNL_UCBCN(array('dsn'=>$this->dsn));
        $u = $b->getUser('bbieber2');
        $this->assertEquals('bbieber2', $u->uid);
    }
}

?>