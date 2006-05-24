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
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $user_has_permission_user_uid;    // string(100)  not_null
    public $user_has_permission_permission_id;    // int(11)  not_null
    public $name;                            // string(100)  
    public $shortname;                       // string(100)  
    public $streetaddress1;                  // string(255)  
    public $streetaddress2;                  // string(255)  
    public $city;                            // string(100)  
    public $state;                           // string(2)  
    public $zip;                             // string(10)  
    public $phone;                           // string(50)  
    public $fax;                             // string(50)  
    public $email;                           // string(100)  
    public $eventreleasepreference;          // string(100)  
    public $accountstatus;                   // string(100)  
    public $calendardaterange;               // string(100)  
    public $formatcalendardata;              // blob(-1)  blob
    public $emaillists;                      // blob(-1)  blob
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Account',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
