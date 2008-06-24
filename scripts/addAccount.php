<?php
/**
 * This file will add an account.
 *
 * PHP version 5
 * 
 * @category  Default 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2007 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
        echo <<<E
This program requires 3 arguments.
addAccount.php account_name

Example: addAccount.php "College of Engineering" 
That will create an account named "College of Engineering"

E;
} else {
        require_once 'UNL/UCBCN.php';
        $b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));
        $a = $b->factory('account');
        $a->name = $_SERVER['argv'][1];
        if ($a->find()) {
            echo $_SERVER['argv'][1].' already exists!'.PHP_EOL;
            exit(1);
        }
        if ($b->createAccount(array('name'=>$_SERVER['argv'][1]))) {
            echo 'Successfully created '.$_SERVER['argv'][1].PHP_EOL;
        }
}

exit();

