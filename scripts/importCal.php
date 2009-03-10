<?php

require_once 'UNL/UCBCN.php';

$b = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));

$u            = $b->getUser('bbieber2');
$c            = UNL_UCBCN::factory('calendar');

$c->shortname = 'extension-inservice';

if ($c->find() == 1) {
    $c->fetch();
} else {
    echo 'Could not find calendar!'.PHP_EOL;
    exit();
}
/*
$e =& UNL_UCBCN::factory('event');
$e->whereAdd("privatecomment LIKE 'Imported from old IANR Calendar HASH:%' AND uidlastupdated='bbieber2'");
if ($e->find()) {
    while ($e->fetch()) {
        $c->removeEvent($e);
        $e->delete();
    }
}
*/
$link = mysql_connect('localhost','test','fishstick');
mysql_select_db('test');
$result = mysql_query('SELECT * FROM tab_extn_cal WHERE cal_name=\'Inservice\';') or die('Query failed: ' . mysql_error());

echo PHP_EOL.'Importing records...'.PHP_EOL;

while ($row = mysql_fetch_assoc($result)) {
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
    
    /*
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
	unset($row['subindex']);
	unset($row['item_index']);
	unset($row['cal_name']);
	unset($row['xindex']);
    unset($row['idate']);
    unset($row['shour']);
    unset($row['ehour']);
	$e                  =& UNL_UCBCN::factory('event');
	$e->title           = $row['string'];
	$e->uidcreated		= $u->uid;
	$e->uidlastupdated	= $u->uid;
	$e->approvedforcirculation = 1;
	$e->privatecomment	= 'Imported from old IANR Calendar HASH:'.md5(serialize($row));
	$event_exists = $e->find();
	if ($event_exists) {
	    $e->fetch();
	    addDateTime($b, $e, $starttime, $endtime);
	} else {
	    // insert?
	    if ($row['notes'] != 0) {
	        $e->description = str_replace('<BR>',"\n",$row['notes']);
	    }
	    if ($row['link'] != 0) {
	        $e->webpageurl = $row['link'];
	    }
		if ($e->insert()) {
		    echo 'I';
		    $c->addEvent($e,'posted',$u, 'create event form');
		    addDateTime($b, $e, $starttime, $endtime);
		}
	}

}

function addDateTime($b, $e, $starttime, $endtime)
{
    $dt =& UNL_UCBCN::factory('eventdatetime');
	$dt->event_id		= $e->id;
	$dt->starttime		= $starttime;
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
