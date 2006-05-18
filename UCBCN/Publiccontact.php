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
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned
    public $Name;                            // string(100)  
    public $JobTitle;                        // string(100)  
    public $Organization;                    // string(100)  
    public $AddressLine1;                    // string(255)  
    public $AddressLine2;                    // string(255)  
    public $City;                            // string(100)  
    public $State;                           // string(2)  
    public $Zip;                             // string(10)  
    public $EmailAddress;                    // string(100)  
    public $Phone;                           // string(50)  
    public $Fax;                             // string(50)  
    public $WebPageURL;                      // blob(65535)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Publiccontact',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
