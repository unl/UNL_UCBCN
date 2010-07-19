#!/usr/bin/env php
<?php
/**
 * Sample script for adding calendars.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brian Steere
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 4) {
        echo "This program requires 3 arguments.\n";
        echo "add_cal.php username calendar_name calendar_shortname\n\n";
        echo "Example: add_cal.php jdoe Organization organizations\n";
        echo "That will create a calendar named Organization with a\n";
        echo "short name of organizations and assign that calendar\n";
        echo "to user jdoe\n";
} else {
        require_once 'UNL/UCBCN.php';
        $b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));
        $u = $b->getUser($_SERVER['argv'][1]);
        echo 'Account ID is '.$u->account_id."\n";

        $a = $b->getAccount($u);
        echo "User's Name: " . $a->name . "\n";
        $a->addCalendar($_SERVER['argv'][2], $_SERVER['argv'][3],$u);
        echo "{$_SERVER['argv'][2]} with shortname {$_SERVER['argv'][3]} has been added for {$_SERVER['argv'][1]}.\n";
}

exit();
