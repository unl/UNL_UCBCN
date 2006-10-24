<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'UNL/UCBCN.php';

class InstallTest extends PHPUnit_Framework_TestCase
{
    public $dsn = 'sqlite://events:password@localhost/events.db';
    public function testBackendInstallScript()
    {
		require_once 'PEAR.php';
		require_once 'UNL/UNL_UCBCN_setup.php';

		$installer = new UNL_UCBCN_setup_postinstall();
		$res = $installer->createDatabase(array('dbtype'=>'sqlite','user'=>'events','password'=>'password','dbhost'=>'localhost','database'=>'events.db'));
		$this->assertFalse(PEAR::isError($res));
		$installer->setupPermissions(array());
    }
    
    public function testPermissions()
    {
        $b = new UNL_UCBCN(array('dsn'=>$this->dsn));
        $p = $b->factory('permission');
        $this->assertNotSame($p,0);
    }
    
    public function testBackendInfo()
    {
        $b = new UNL_UCBCN(array('dsn'=>$this->dsn));
        $u = $b->getUser('bbieber2');
        $this->assertEquals($u->uid, 'bbieber2');
    }
}
?>