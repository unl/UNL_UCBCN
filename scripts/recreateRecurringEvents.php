<?php

ini_set('display_errors', false);
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE);

/*Add a custom include path here if needed....*/
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__).'/../includes/backend',
    dirname(__FILE__).'/../includes/manager',
    dirname(__FILE__).'/../includes/frontend',
    dirname(__FILE__).'/../includes/xml2json',
    dirname(__FILE__).'/../includes/pear')));

require_once 'UNL/UCBCN/Autoload.php';

$ucbcn = new UNL_UCBCN(array('dsn' => "mysqli://root@localhost/events"));

// get all event datetimes with recurring
$eventdatetime = UNL_UCBCN::factory('eventdatetime');

$eventdatetime->whereAdd("recurringtype != 'none'");
$eventdatetime->find();

while ($eventdatetime->fetch()) {
	echo "doing edt# " . $eventdatetime->id . PHP_EOL;
	$eventdatetime->update();
}

echo 'DONE';
