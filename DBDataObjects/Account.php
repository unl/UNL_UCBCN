<?php
/**
 * Table Definition for account
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Account extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'account';                         // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $User_has_Permission_User_CalNetUID;    // string(100)  not_null
    public $User_has_Permission_Permission_ID;    // int(10)  not_null multiple_key unsigned
    public $Name;                            // string(100)  
    public $ShortName;                       // string(100)  
    public $StreetAddress1;                  // string(255)  
    public $StreetAddress2;                  // string(255)  
    public $City;                            // string(100)  
    public $State;                           // string(2)  
    public $Zip;                             // string(10)  
    public $Phone;                           // string(50)  
    public $Fax;                             // string(50)  
    public $Email;                           // string(100)  
    public $EventReleasePreference;          // string(100)  
    public $AccountStatus;                   // string(100)  
    public $CalendarDateRange;               // string(100)  
    public $FormatCalendarData;              // blob(65535)  blob
    public $EmailLists;                      // blob(65535)  blob
    public $DateCreated;                     // datetime(19)  
    public $CalNetUIDCreated;                // string(100)  
    public $DateLastUpdated;                 // timestamp(14)  not_null unsigned zerofill timestamp
    public $CalNetUIDLastUpdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Account',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
