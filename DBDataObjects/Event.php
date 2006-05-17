<?php
/**
 * Table Definition for event
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event';                           // table name
    public $ID;                              // int(10)  not_null primary_key unsigned
    public $EventType_ID;                    // int(10)  not_null multiple_key unsigned
    public $Title;                           // string(100)  
    public $Subtitle;                        // string(100)  
    public $OtherType;                       // string(255)  
    public $Subtype;                         // string(100)  
    public $Description;                     // blob(65535)  blob
    public $ShortDescription;                // string(255)  
    public $Refreshments;                    // string(255)  
    public $NetworkClassification;           // string(100)  
    public $ApprovedForCirculation;          // int(1)  
    public $Status;                          // string(100)  
    public $OwnerID;                         // int(10)  unsigned
    public $PrivateComment;                  // blob(65535)  blob
    public $OtherKeyword;                    // string(255)  
    public $ImageTitle;                      // string(100)  
    public $ImageURL;                        // blob(65535)  blob
    public $WebPageURL;                      // blob(65535)  blob
    public $ListingContactCalNetUID;         // string(100)  
    public $DateCreated;                     // datetime(19)  
    public $CalNetUIDCreated;                // string(100)  
    public $DateLastUpdated;                 // timestamp(14)  not_null unsigned zerofill timestamp
    public $CalNetUIDLastUpdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
