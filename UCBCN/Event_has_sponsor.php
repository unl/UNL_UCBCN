<?php
/**
 * Table Definition for event_has_sponsor
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Event_has_sponsor extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event_has_sponsor';               // table name
    public $event_id;                        // int(11)  not_null
    public $sponsor_id;                      // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event_has_sponsor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
