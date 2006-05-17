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
    public $Account_ID;                      // int(10)  not_null primary_key multiple_key unsigned
    public $Event_ID;                        // int(10)  not_null primary_key multiple_key unsigned
    public $Status;                          // string(100)  
    public $Source;                          // string(100)  
    public $DateCreated;                     // datetime(19)  
    public $CalNetUIDCreated;                // string(100)  
    public $DateLastUpdated;                 // timestamp(14)  not_null unsigned zerofill timestamp
    public $CalNetUIDLastUpdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Account_has_event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
