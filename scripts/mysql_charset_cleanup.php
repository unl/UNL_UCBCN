<?php
header('Content-type:text/html;charset=UTF-8');
set_include_path(implode(PATH_SEPARATOR, array( 
    dirname(dirname(__FILE__)).'/includes/pear',
    dirname(dirname(__FILE__)).'/includes/backend',
    get_include_path())));

require_once 'UNL/UCBCN.php';
$backend = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/events'));

mb_internal_encoding("UTF-8");
mb_detect_order("UTF-8, ISO-8859-1");

$db =& $backend->getDatabaseConnection();

$ids = $db->queryCol('SELECT id FROM event');
foreach($ids as $id) {
    compareDetails($db, $id);
}

function compareDetails(&$db, $id) {
    $db->query("SET NAMES latin1");
    $l1result = $db->queryRow("SELECT description FROM event WHERE id=$id;");
    $db->query("SET NAMES utf8");
    $utf8result = $db->queryRow("SELECT description FROM event WHERE id=$id;");
    
    if ($l1result[0] != $utf8result[0]) {
        // Uhoh, which is correct?
        
        if (mb_detect_encoding($l1result[0], "UTF-8", true) !== false) {
            // This appears correct in latin 1, let's convert it to UTF8
            var_dump($id);
            echo '<br />';
            var_dump($l1result[0]);
            echo '<br />';
            var_dump($utf8result[0]);
            echo '<hr />';
            $db->exec('UPDATE event SET description = "'.$db->escape($l1result[0]).'" WHERE id='.$id);
        }
    }
}

