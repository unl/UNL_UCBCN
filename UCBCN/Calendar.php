<?php
/**
 * Table Definition for calendar
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Calendar extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar';                        // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $account_id;                      // int(10)  not_null unsigned
    public $name;                            // string(255)  
    public $shortname;                       // string(100)  
    public $eventreleasepreference;          // string(255)  
    public $calendardaterange;               // int(10)  unsigned
    public $formatcalendardata;              // blob(-1)  blob
    public $uploadedcss;                     // blob(-1)  blob
    public $uploadedxsl;                     // blob(-1)  blob
    public $emaillists;                      // blob(-1)  blob
    public $calendarstatus;                  // string(255)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(255)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(255)  
    public $externalforms;                   // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Calendar',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
