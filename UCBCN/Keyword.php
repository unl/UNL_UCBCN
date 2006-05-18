<?php
/**
 * Table Definition for keyword
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Keyword extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'keyword';                         // table name
    public $ID;                              // int(10)  not_null primary_key unsigned
    public $Name;                            // string(100)  not_null
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Keyword',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
