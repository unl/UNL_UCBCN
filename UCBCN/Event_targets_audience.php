<?php
/**
 * Table Definition for event_targets_audience
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Event_targets_audience extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event_targets_audience';          // table name
    public $event_id;                        // int(10)  not_null unsigned
    public $audience_id;                     // int(10)  not_null unsigned

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event_targets_audience',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
