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
    public $id;                              // int(11)  not_null
    public $eventtype_id;                    // int(11)  not_null
    public $title;                           // string(100)  
    public $subtitle;                        // string(100)  
    public $othertype;                       // string(255)  
    public $subtype;                         // string(100)  
    public $description;                     // blob(-1)  blob
    public $shortdescription;                // string(255)  
    public $refreshments;                    // string(255)  
    public $networkclassification;           // string(100)  
    public $approvedforcirculation;          // int(1)  
    public $status;                          // string(100)  
    public $ownerid;                         // int(11)  
    public $privatecomment;                  // blob(-1)  blob
    public $otherkeyword;                    // string(255)  
    public $imagetitle;                      // string(100)  
    public $imageurl;                        // blob(-1)  blob
    public $webpageurl;                      // blob(-1)  blob
    public $listingcontactcalnetuid;         // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $calnetuidcreated;                // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $calnetuidlastupdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
