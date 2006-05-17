<?php
/**
 * Table Definition for user
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_User extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user';                            // table name
    public $CalNetUID;                       // string(100)  not_null primary_key
    public $Account_ID;                      // int(10)  not_null multiple_key unsigned
    public $AccountStatus;                   // string(100)  
    public $DateCreated;                     // datetime(19)  
    public $CalNetUIDCreated;                // string(100)  
    public $DateLastUpdated;                 // timestamp(14)  not_null unsigned zerofill timestamp
    public $CalNetUIDLastUpdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_User',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
