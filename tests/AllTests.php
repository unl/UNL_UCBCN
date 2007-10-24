<?php
/**
 * Test suite for UNL_UCBCN
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

require_once dirname(__FILE__) . '/UNL/UCBCNTest.php';
require_once dirname(__FILE__) . '/InstallTest.php';
require_once dirname(__FILE__) . '/SchemaTest.php';
require_once dirname(__FILE__) . '/UNL/UCBCN/AccountTest.php';
require_once dirname(__FILE__) . '/UNL/UCBCN/CalendarTest.php';
require_once dirname(__FILE__) . '/UNL/UCBCN/EventInstanceTest.php';
require_once dirname(__FILE__) . '/UNL/UCBCN/UserTest.php';

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
     * Adds the UNL_UCBCNTest suite.
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
        $suite->addTestSuite('UNL_UCBCN_AccountTest');
        $suite->addTestSuite('UNL_UCBCN_CalendarTest');
        $suite->addTestSuite('UNL_UCBCN_EventInstanceTest');
        $suite->addTestSuite('UNL_UCBCN_UserTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'UNL_UCBCN_AllTests::main') {
    UNL_UCBCN_AllTests::main();
}
?>