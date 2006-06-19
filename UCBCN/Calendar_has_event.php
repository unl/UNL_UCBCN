<?php
/**
 * Table Definition for calendar_has_event
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Calendar_has_event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar_has_event';              // table name
    public $calendar_id;                     // int(10)  not_null unsigned
    public $event_id;                        // int(10)  not_null unsigned
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
    	return parent::insert();
    }
    
    function update()
    {
    	$this->datelastupdated = date('Y-m-d H:i:s');
    	return parent::update();
    }
}
