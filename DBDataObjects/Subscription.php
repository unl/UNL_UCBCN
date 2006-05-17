<?php
/**
 * Table Definition for subscription
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Subscription extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'subscription';                    // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Account_ID;                      // int(10)  not_null multiple_key unsigned
    public $Name;                            // string(100)  
    public $AutomaticApproval;               // int(1)  not_null
    public $TimePeriod;                      // date(10)  
    public $ExpirationDate;                  // date(10)  
    public $SearchCriteria;                  // blob(65535)  blob
    public $DateCreated;                     // datetime(19)  
    public $CalNetUIDCreated;                // string(100)  
    public $DateLastUpdated;                 // timestamp(14)  not_null unsigned zerofill timestamp
    public $CalNetUIDLastUpdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Subscription',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
