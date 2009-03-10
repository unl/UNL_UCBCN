<?php
/**
 * Sample script for adding sponsors.
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
    || $_SERVER['argv'][1] == '--help' || !($_SERVER['argc'] == 2 || $_SERVER['argc'] == 3)){
	echo "This program requires 1 or 2 arguments.\n";
	echo "addSponsor.php sponsor_name [url]\n\n";
	echo "Example: addSponsor.php Athletics http://example.com/athletics\n";
	echo "That will create a sponsor named Athletics and set their\n";
	echo "url to http://example.com/athletics\n";
	echo "The URL is optional. If the sponsor already exists, it will be updated \n";
	echo "with the new url provided\n";
}else{
	require_once 'UNL/UCBCN.php';
	$backend = new UNL_UCBCN(array('dsn'=>'mysql://eventcal:eventcal@localhost/eventcal'));
	// Backend is a UNL_UCBCN object.
	$sponsor = UNL_UCBCN::factory('sponsor');

	if (isset($_SERVER['argv'][2])) {
		$url = $_SERVER['argv'][2];
	} else {
		$url = 'no url';
	}

	$sponsor->name = $_SERVER['argv'][1];
	if (!$sponsor->find()) {
		// Couldn't find an existing record for 'blah'
		// Assign all the details for a record in the database.
		$sponsor->name = $_SERVER['argv'][1];
		$sponsor->webpageurl = $_SERVER['argv'][2];
		$sponsor->insert();
		echo "{$_SERVER['argv'][1]} has been added with {$url}\n";
	} else {
		// found a record for 'Blah', we can update the details.
		$sponsor->fetch();
		$sponsor->webpageurl = $_SERVER['argv'][2];
		$sponsor->update();
		echo "{$_SERVER['argv'][1]} has be updated with {$url}\n";
	}
}

?>