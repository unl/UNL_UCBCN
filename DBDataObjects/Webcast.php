<?php
/**
 * Table Definition for webcast
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Webcast extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'webcast';                         // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned
    public $Title;                           // string(100)  
    public $Status;                          // string(100)  
    public $DateAvailable;                   // datetime(19)  
    public $PlayerType;                      // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Webcast',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
