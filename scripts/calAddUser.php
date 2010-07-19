#!/usr/bin/env php
<?php
/**
 * Sample script for adding calendars.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 3) {
    echo "This program requires 2 arguments.\n";
    echo "calAddUser.php calendar_shortname username\n\n";
    echo "Example: calAddUser.php chemistry jdoe\n";
    echo "That will add jdoe to the chemsitry calendar\n";
    echo "with all permissions.\n";
} else {
    require_once 'UNL/UCBCN.php';
    $b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));
    $c = UNL_UCBCN::factory('calendar');
    $c->shortname = $_SERVER['argv'][1];
    if ($c->find() != 1) {
        echo "That calendar does not exist!\n\n";
        echo "You can create it with php addCal.php "
            . $_SERVER['argv'][2].' '
            . $_SERVER['argv'][1].' '
            . $_SERVER['argv'][1].PHP_EOL;
        exit();
    }
    $c->fetch();
    
    $u = $b->getUser($_SERVER['argv'][2]);
    
    $c->addUser($u);
    echo "The user {$u->uid} has been added to the {$c->shortname} calendar.\n";
}

exit();