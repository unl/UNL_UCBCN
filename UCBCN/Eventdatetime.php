<?php
/**
 * Table Definition for eventdatetime
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Eventdatetime extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'eventdatetime';                   // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null unsigned
    public $location_id;                     // int(10)  not_null unsigned
    public $starttime;                       // datetime(19)  binary
    public $endtime;                         // datetime(19)  binary
    public $room;                            // string(255)  
    public $hours;                           // string(255)  
    public $directions;                      // blob(-1)  blob
    public $additionalpublicinfo;            // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Eventdatetime',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
