<?php
/**
 * Table Definition for account_has_event
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Account_has_event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'account_has_event';               // table name
    public $account_id;                      // int(11)  not_null
    public $event_id;                        // int(11)  not_null
    public $status;                          // string(100)  
    public $source;                          // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Account_has_event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
