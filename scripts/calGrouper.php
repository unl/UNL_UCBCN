#!/usr/bin/env php
<?php
/**
 * This script will group calendars all under one account.
 * 
 * Usage: php calGrouper.php "UNL Events" bbieber2 ppeters1
 *
 * PHP version 5
 * 
 * @category  Default 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] < 4) {
        echo <<<E
This program requires a minimum of 3 arguments.
calGrouper.php account_name cal1 cal2 ...

Where account_name is a new or existing account, and cal1 are shortnames
for calendars.

E;

} else {
    require_once 'UNL/UCBCN.php';
    $b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));
    $a = UNL_UCBCN::factory('account');
    if (ctype_digit($_SERVER['argv'][1])) {
        $a->id = $_SERVER['argv'][1];
    } else {
        $a->name = $_SERVER['argv'][1];
    }
    if (!$a->find()) {
        echo 'Could not find that account!'.PHP_EOL;
        exit(1);
    }
    $a->fetch();
    echo 'Account name is '.$a->name.PHP_EOL;
    echo 'Cancel now if this is not correct!'.PHP_EOL;
    sleep(2);
    $cal_count = 2;
    while (isset($_SERVER['argv'][$cal_count])) {
        $c = UNL_UCBCN::factory('calendar');
        $c->shortname = $_SERVER['argv'][$cal_count];
        if (!$c->find()) {
            echo 'Unknown calendar '.$_SERVER['argv'][$cal_count].PHP_EOL;
            exit(1);
        }
        $c->fetch();
        $c->account_id = $a->id;
        if ($c->update()) {
            echo 'Updated '.$c->name.' to the '.$a->name.' account.'.PHP_EOL;
        }
        $cal_count++;
    }
}

exit(0);

