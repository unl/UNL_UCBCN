<?php
/**
 * Table Definition for admissioninfogroup
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Admissioninfogroup extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'admissioninfogroup';              // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $event_id;                        // int(11)  not_null
    public $ticketpolicy;                    // string(100)  
    public $ticketcontactname;               // string(100)  
    public $ticketcontactphone;              // string(50)  
    public $ticketcontacturl;                // blob(-1)  blob
    public $ticketsonsaledate;               // datetime(19)  binary
    public $ticketadditionalinfo;            // string(255)  
    public $reservationpolicy;               // string(100)  
    public $reservationcontactname;          // string(100)  
    public $reservationcontactphone;         // string(50)  
    public $reservationcontacturl;           // blob(-1)  blob
    public $reservationadditionalinfo;       // string(255)  
    public $freeevent;                       // int(4)  
    public $soldout;                         // int(1)  
    public $otheraudience;                   // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Admissioninfogroup',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
