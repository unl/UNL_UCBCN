<?php
/**
 * Table Definition for calendar_has_event
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN.php';

class UNL_UCBCN_Calendar_has_event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar_has_event';              // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $calendar_id;                     // int(10)  not_null multiple_key unsigned
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $status;                          // string(100)  
    public $source;                          // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Calendar_has_event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function insert()
    {
    	$this->datecreated		= date('Y-m-d H:i:s');
    	$this->datelastupdated = date('Y-m-d H:i:s');
    	if (isset($_SESSION['_authsession'])) {
	    	$this->uidcreated=$_SESSION['_authsession']['username'];
	    	$this->uidlastupdated=$_SESSION['_authsession']['username'];
    	}
    	$r = parent::insert();
    	if ($r) {
    		// Clean the cache on successful insert.
    		UNL_UCBCN::cleanCache();
    	}
    	return $r;
    }
    
    function update()
    {
    	$this->datelastupdated = date('Y-m-d H:i:s');
    	if (isset($_SESSION['_authsession'])) {
	    	$this->uidlastupdated=$_SESSION['_authsession']['username'];
    	}
    	$r = parent::update();
    	if ($r) {
    		// Clean the cache on successful update.
    		UNL_UCBCN::cleanCache();
    	}
    	return $r;
    }
    
    /**
     * Returns bool false if the calendar does not have the event, 
     * otherwise returns status.
     *
     * @param UNL_UCBCN_Calendar $calendar
     * @param UNL_UCBCN_Event $event
     */
    function calendarHasEvent($calendar,$event)
    {
        $che = UNL_UCBCN::factory('calendar_has_event');
        $che->calendar_id = $calendar->id;
        $che->event_id = $event->id;
        if ($che->find()) {
            $che->fetch();
            return $che->status;
        } else {
            return false;
        }
    }
    
    function delete()
    {
    	$r = parent::delete();
    	if ($r) {
    		// Clean the cache on successful delete.
    		UNL_UCBCN::cleanCache();
    	}
    	return $r;
    }
}
