<?php
/**
 * Table Definition for eventtype
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Eventtype extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'eventtype';                       // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $calendar_id;                     // int(10)  not_null unsigned
    public $name;                            // string(100)  not_null
    public $description;                     // string(255)  
    public $eventtypegroup;                  // string(8)  
    public $standard;                        // int(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Eventtype',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_linkDisplayFields = array('name');
}
