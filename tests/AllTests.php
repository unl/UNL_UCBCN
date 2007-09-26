<?php
/**
 * Test suite for Services_W3C_HTMLValidator
 *
 * PHP versions 5
 *
 * @category Events
 * @package  UNL_UCBCN
 * @author   Brett Bieber <brett.bieber@gmail.com>
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'UNL_UCBCN_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'UNL/UCBCNTest.php';
require_once 'InstallTest.php';
require_once 'SchemaTest.php';

class UNL_UCBCN_AllTests
{
	/**
     * Runs the test suite.
     *
     * @return unknown
     */
    public static function main()
    {

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Adds the Services_W3C_HTMLValidatorTest suite.
     *
     * @return $suite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('UNL_UCBCN tests');
        /** Add testsuites, if there is. */
        $suite->addTestSuite('UNL_UCBCN_SchemaTest');
        $suite->addTestSuite('UNL_UCBCN_InstallTest');
        $suite->addTestSuite('UNL_UCBCNTest');
        

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'UNL_UCBCN_AllTests::main') {
    UNL_UCBCN_AllTests::main();
}
?>