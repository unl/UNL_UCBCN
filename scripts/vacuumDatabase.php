<?php
require_once 'UNL/UCBCN.php';
global $unl;
$unl = new UNL_UCBCN(array('dsn'=>'mysqli://eventcal:eventcal@localhost/eventcal'));

/**
 * Remove an event
 *
 * @param int $id
 */
function removeEvent ( $id ) {
    global $unl ;
    
    $event = UNL_UCBCN::factory ( 'event' ) ;
    if ($event->get ( $id )) {
        echo 'Found event, id is '.$id.PHP_EOL;
        if ($event->delete()) {
            echo 'Deleted'.PHP_EOL;
        }
    }
}

/**
 * Remove all events which:
 * -Have no date/time association
 * -Are orphaned
 *
 */
function clean(){
    $orphans = getOrphanedEvents();
    $noDT = getEventNoDT();
    
    foreach($orphans as $event){
        removeEvent($event['id']);
    }
    
    foreach($noDT as $event){
        removeEvent($event['id']);
    }
}

/**
 * Get Events with no associated datetime
 *
 * @return array
 */
function getEventNoDT () {
    global $unl ;
    
    $event = UNL_UCBCN::factory ( 'event' ) ;
    $event->query ( "SELECT * FROM event e WHERE e.id NOT IN (SELECT DISTINCT event_id FROM eventdatetime)" ) ;
    while ( $event->fetch () ) {
        $temp = array ( ) ;
        $temp [ 'id' ] = $event->id ;
        $temp [ 'title' ] = $event->title ;
        $temp [ 'uidcreated' ] = $event->uidcreated ;
        $temp [ 'uidlastupdated' ] = $event->uidlastupdated ;
        
        $e_array [] = $temp ;
    }
    
    return $e_array ;
}

/**
 * Get a list of all orphaned events
 *
 * @return array
 */
function getOrphanedEvents () {
    global $unl ;
    
    $e_array = array();
    
    $event = UNL_UCBCN::factory ( 'event' ) ;
    while ( $event->fetch () ) {
        if ($event->isOrphaned ()) {
            $temp = array ( ) ;
            $temp [ 'id' ] = $event->id ;
            $temp [ 'title' ] = $event->title ;
            $temp [ 'uidcreated' ] = $event->uidcreated ;
            $temp [ 'uidlastupdated' ] = $event->uidlastupdated ;
            
            $e_array [] = $temp ;
        }
    }
    
    return $e_array;
}

clean();

?>