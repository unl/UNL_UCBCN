<?php
/**
 * Table Definition for location
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Location extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'location';                        // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Name;                            // string(100)  
    public $StreetAddress1;                  // string(255)  
    public $StreetAddress2;                  // string(255)  
    public $Room;                            // string(100)  
    public $City;                            // string(100)  
    public $State;                           // string(2)  
    public $Zip;                             // string(10)  
    public $MapURL;                          // blob(65535)  blob
    public $WebPageURL;                      // blob(65535)  blob
    public $Hours;                           // string(255)  
    public $AdditionalPublicInfo;            // string(255)  
    public $Type;                            // string(100)  
    public $Phone;                           // string(50)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Location',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
