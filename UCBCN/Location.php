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
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(100)  
    public $streetaddress1;                  // string(255)  
    public $streetaddress2;                  // string(255)  
    public $room;                            // string(100)  
    public $city;                            // string(100)  
    public $state;                           // string(2)  
    public $zip;                             // string(10)  
    public $mapurl;                          // blob(-1)  blob
    public $webpageurl;                      // blob(-1)  blob
    public $hours;                           // string(255)  
    public $directions;                      // blob(-1)  blob
    public $additionalpublicinfo;            // string(255)  
    public $type;                            // string(100)  
    public $phone;                           // string(50)  
    public $standard;                        // int(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Location',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_linkDisplayFields		= array('name');
}
