#!/usr/bin/env php
<?php
/**
 * This is a sample script which was used to import events from an RSS feed of
 * athletics events. This script covers the basics of importing events into the
 * Event Publisher and adding events to a specific calendar.
 * 
 * Modify for your own needs.
 */
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once 'UNL/UCBCN.php';

$backend  = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));

//DB_DataObject::debugLevel(5);

$user     = $backend->getUser('bbieber2');
$calendar = UNL_UCBCN::factory('calendar');

$calendar->shortname = 'athletics';

if ($calendar->find() != 1) {
    echo 'Could not find calendar!'.PHP_EOL;
    exit();
}

$calendar->fetch();

echo PHP_EOL.'Importing records...'.PHP_EOL;

$athletics = file_get_contents('http://www.huskers.com/rss.dbml?db_oem_id=100&media=schedulesxml');

$xml = new SimpleXMLElement($athletics);

foreach ($xml->channel->item as $event_xml) {

    $starttime = 0;
    $endtime   = null;

    if ($event_xml->time == 'TBA') {

        // TBA event, how to handle?

    }

    if ($event_xml->homeaway != 'H') {
        //Only import home events
        continue;
    }

    $starttime = date('Y-m-d H:i:s', strtotime($event_xml->date));

    $e                         =& UNL_UCBCN::factory('event');
    $e->title                  = (string)$event_xml->sport;
    $e->uidcreated             = $user->uid;
    $e->uidlastupdated         = $user->uid;
    $e->approvedforcirculation = 1;
    $e->privatecomment         = 'Imported from athletics rss feed HASH:'.md5($event_xml->guid);

    $location =& UNL_UCBCN::factory('location');
    $location->name = $event_xml->location;
    if (!$location->find()) {
        $location->insert();
    } else {
        $location->fetch();
    }

    $event_exists = $e->find();

    $additional_info = '';
    if (!empty($event_xml->tv)) {
        $additional_info = $event_xml->tv;
    }

    if ($event_exists) {
        $e->fetch();
        addDateTime($e, $starttime, $endtime, $location, $additional_info);
    } else {
        // insert?
        $e->description = 'Nebraska Cornhuskers vs. '.$event_xml->opponent;

        $e->webpageurl = preg_replace('/\&SPSID=[\d]+\&Q_SEASON=[\d]+/', '', $event_xml->link);

        if ($e->insert()) {
            echo 'I';
            $calendar->addEvent($e, 'posted', $user, 'create event form');
            addDateTime($e, $starttime, $endtime, $location, $additional_info);
        }
    }

}

function addDateTime($e, $starttime, $endtime, $location, $additional_info)
{
    $dt =& UNL_UCBCN::factory('eventdatetime');
    $dt->event_id  = $e->id;
    $dt->starttime = $starttime;

    if ($endtime) {
        $dt->endtime = $endtime;
    }

    if (!$dt->find()) {
        $dt->location_id = $location->id;
        $dt->additionalpublicinfo = $additional_info;
        return $dt->insert();
    }

    return true;
}

echo PHP_EOL.'DONE'.PHP_EOL;

