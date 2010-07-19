#!/usr/bin/env php
<?php
/**
 * This is a sample script which was used to import events from an old calendar
 * system. This script covers the basics of importing events into the Event
 * Publisher and adding events to a specific calendar.
 * 
 * Modify for your own needs.
 */
require_once 'UNL/UCBCN.php';

// Set up the backend
$b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));

// Get the user all these events will be created by.
$u = $b->getUser('bbieber2');

$c = UNL_UCBCN::factory('calendar');
// Here we specify the shortname of a specific calendar we want to add events to
$c->shortname = 'extension-inservice';

if ($c->find() == 1) {
    // Success, we've found the calendar.
    $c->fetch();
} else {
    echo 'Could not find calendar!'.PHP_EOL;
    exit();
}
/*
 * If you'd like to delete events already imported, you can find a specific set
 * of events and remove them. This can be helpful during testing until you get
 * the import script functioning exactly how you want it to work. Notice the
 * use of the privatecomment field to note the imported events.
 * 
$e =& UNL_UCBCN::factory('event');
$e->whereAdd("privatecomment LIKE 'Imported from old IANR Calendar HASH:%' AND uidlastupdated='bbieber2'");
if ($e->find()) {
    while ($e->fetch()) {
        $c->removeEvent($e);
        $e->delete();
    }
}
*/

// Here we connect to the old calendar database, and find the events.
$link = mysql_connect('localhost','test','fishstick');
mysql_select_db('test');
$result = mysql_query('SELECT * FROM tab_extn_cal WHERE cal_name=\'Inservice\';') or die('Query failed: ' . mysql_error());

echo PHP_EOL.'Importing records...'.PHP_EOL;

while ($row = mysql_fetch_assoc($result)) {
    
    /* Here's an example of a row from the old database.
    Array
    (
        [cal_name] => All
        [xindex] => 9319
        [item_index] => 5355
        [subindex] => 2
        [modified] => 1
        [user] => admin
        [idate] => 20031113
        [shour] => 0700
        [ehour] => 0
        [string] => National Agrability Training Workshop - Omaha, NE
        [link] => 0
        [rsec] => 0
        [notes] => 0
        [local] => 
    )*/
    
    // The old system had a complicated way of storing the date & time.
    if ($row['shour']) {
        $starttime = date('Y-m-d H:i:s',strtotime($row['idate'].' '.$row['shour']));
    } else {
        $starttime = date('Y-m-d H:i:s',strtotime($row['idate']));
    }
    if (strpos($starttime,'1969-12-31') === false) {
        // Date should be correct.
    } else {
        continue;
    }
    if ($row['ehour']) {
        $endtime = substr($starttime,0,10).' '.$row['ehour'];
    } else {
        $endtime = 0;
    }
    
    // Here we set up the event object, assigning the appropriate details.
    $e                         =& UNL_UCBCN::factory('event');
    $e->title                  = $row['string'];
    $e->uidcreated             = $u->uid;
    $e->uidlastupdated         = $u->uid;
    $e->approvedforcirculation = 1;
    
    // Use the privatecomment field to indicate this specific batch of import
    $e->privatecomment         = 'Imported from old IANR Calendar HASH:'.md5(serialize($row));
    
    // Before we insert, check to see if the event exists or is the same.
    $event_exists = $e->find();
    
    if ($event_exists) {
        $e->fetch();
        addDateTime($b, $e, $starttime, $endtime);
    } else {
        // insert a new event
        if ($row['notes'] != 0) {
            $e->description = str_replace('<BR>',"\n",$row['notes']);
        }
        if ($row['link'] != 0) {
            $e->webpageurl = $row['link'];
        }
        if ($e->insert()) {
            echo 'I';
            // Event has been inserted to the system, now add it to the calendar.
            $c->addEvent($e, 'posted', $u, 'create event form');
            
            // Be sure to add the date & time for the event.
            addDateTime($b, $e, $starttime, $endtime);
        }
    }

}

/**
 * Because one event can have multiple dates & times, we add it separately.
 * 
 * @param UNL_UCBCN       $b         The backend
 * @param UNL_UCBCN_Event $e         The event
 * @param string          $starttime Event start time in MySQL date format YYYY-MM-DD HH:ii
 * @param string          $endtime   Event end time (optional)
 * 
 * @return bool
 */
function addDateTime(UNL_UCBCN $b, UNL_UCBCN_Event $e, $starttime, $endtime)
{
    $dt =& UNL_UCBCN::factory('eventdatetime');
    $dt->event_id  = $e->id;
    $dt->starttime = $starttime;
    if ($endtime) {
        $dt->endtime = $endtime;
    }
    if (!$dt->find()) {
        return $dt->insert();
    } else {
        return true;
    }
}

echo PHP_EOL.'DONE'.PHP_EOL;

?>
