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

/**
 * InstallTest class.
 *
 * @package UNL_UCBCN
 */
class InstallTest extends PHPUnit_Framework_TestCase
{
    /**
     * Database connection string for the sqlite test database.
     *
     * @var string
     */
    public $dsn = 'sqlite:///events';
    
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
		$res = $installer->createDatabase(array('dbtype'=>'sqlite','user'=>'events','password'=>'password','dbhost'=>'localhost','database'=>'events'));
		$this->assertFalse(PEAR::isError($res));
		$installer->setupPermissions(array());
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
        $this->assertNotEquals($p->find(),0);
    }
    
    /**
     * Test that the backend can get a simple user.
     *
     */
    public function testBackendInfo()
    {
        $b = new UNL_UCBCN(array('dsn'=>$this->dsn));
        
        $a = $b->factory('account');
        echo $a->find();
        //DB_DataObject::debugLevel(2);
        $u = $b->getUser('bbieber2');
        
        $this->assertEquals($u->uid, 'bbieber2');
    }
}
?>