<?php
/**
 * Table Definition for admissioncharge
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Admissioncharge extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'admissioncharge';                 // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $admissioninfogroup_id;           // int(11)  not_null
    public $price;                           // string(100)  
    public $description;                     // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Admissioncharge',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
