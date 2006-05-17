<?php
/**
 * Table Definition for attendancerestriction
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Attendancerestriction extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'attendancerestriction';           // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned
    public $Name;                            // string(100)  
    public $Description;                     // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Attendancerestriction',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
