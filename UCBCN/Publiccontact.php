<?php
/**
 * Table Definition for publiccontact
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Publiccontact extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'publiccontact';                   // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null unsigned
    public $name;                            // string(100)  
    public $jobtitle;                        // string(100)  
    public $organization;                    // string(100)  
    public $addressline1;                    // string(255)  
    public $addressline2;                    // string(255)  
    public $room;                            // string(255)  
    public $city;                            // string(100)  
    public $state;                           // string(2)  
    public $zip;                             // string(10)  
    public $emailaddress;                    // string(100)  
    public $phone;                           // string(50)  
    public $fax;                             // string(50)  
    public $webpageurl;                      // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Publiccontact',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
