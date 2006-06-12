<?php
/**
 * Table Definition for session
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Session extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'session';                         // table name
    public $user_uid;                        // string(255)  not_null primary_key
    public $lastaction;                      // datetime(19)  not_null binary
    public $data;                            // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Session',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
