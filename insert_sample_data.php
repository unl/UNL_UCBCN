<?php
/**
 * This file is intended to insert some sample data into the calendar system.
 */
require_once 'UNL/UCBCN.php';
require_once 'UNL/Common/Building.php';

error_reporting(E_ALL);

$backend = new UNL_UCBCN(array('dsn'=>'mysql://eventcal:eventcal@localhost/eventcal'));

echo '<pre>';
DB_DataObject::debugLevel(1);

/** Populate some locations in the database */
$bldgs = new UNL_Common_Building();
foreach ($bldgs->codes as $code=>$name) {
	$location =& $backend->factory('location');
	$location->name = $name;
	if (!$location->find()) {
		$location->city		= 'Lincoln';
		$location->state	= 'NE';
		$location->name		= $name;
		$location->additionalpublicinfo = $code;
		$location->insert();
	} else {
		echo "Sorry, $name already exists.\n";
	}
}

/** Add some event types to the database */
$eventtype = $backend->factory('eventtype');
$types = array('Career Fair',
			'Colloquium',
			'Conference/Symposium',
			'Course',
			'Deadline',
			'Debate/Panel Discussion',
			'Exhibit - Artifacts',
			'Exhibit - Multimedia',
			'Exhibit - Painting',
			'Exhibit - Photography',
			'Exhibit - Sculpture',
			'Film - Animated',
			'Film - Documentary',
			'Film - Feature',
			'Film - Series',
			'Film - Short',
			'Holiday',
			'Information Session',
			'Lecture',
			'Meeting',
			'Memorial',
			'Other',
			'Performing Arts - Dance',
			'Performing Arts - Music',
			'Performing Arts - Other',
			'Performing Arts - Theater',
			'Presentation',
			'Reading - Fiction/poetry',
			'Reading - Nonfiction',
			'Reception',
			'Sale',
			'Seminar',
			'Social Event',
			'Special Event',
			'Sport - Club',
			'Sport - Intercollegiate - Baseball/Softball',
			'Sport - Intercollegiate - Basketball',
			'Sport - Intercollegiate - Crew',
			'Sport - Intercollegiate - Cross Country',
			'Sport - Intercollegiate - Football',
			'Sport - Intercollegiate - Golf',
			'Sport - Intercollegiate - Gymnastics',
			'Sport - Intercollegiate - Rugby',
			'Sport - Intercollegiate - Soccer',
			'Sport - Intercollegiate - Swimming & Diving',
			'Sport - Intercollegiate - Tennis',
			'Sport - Intercollegiate - Track & Field',
			'Sport - Intercollegiate - Volleyball',
			'Sport - Intramural',
			'Sport - Recreational',
			'Tour/Open House',
			'Workshop');

foreach ($types as $type) {
	$eventtype->name = $type;
	if (!$eventtype->find()) {
		$eventtype->name = $type;
		$eventtype->description = $type;
		$eventtype->insert();
	}
}

