<?php
/**
 * Table Definition for relatedevent
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Relatedevent extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'relatedevent';                    // table name
    public $event_id;                        // int(11)  not_null
    public $relatedeventid;                  // int(11)  not_null
    public $relationtype;                    // string(100)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Relatedevent',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
