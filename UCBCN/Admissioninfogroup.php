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
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned
    public $TicketPolicy;                    // string(100)  
    public $TicketContactName;               // string(100)  
    public $TicketContactPhone;              // string(50)  
    public $TicketContactURL;                // blob(65535)  blob
    public $TicketsOnSaleDate;               // datetime(19)  
    public $TicketAdditionalInfo;            // string(255)  
    public $ReservationPolicy;               // string(100)  
    public $ReservationContactName;          // string(100)  
    public $ReservationContactPhone;         // string(50)  
    public $ReservationContactURL;           // blob(65535)  blob
    public $ReservationAdditionalInfo;       // string(255)  
    public $FreeEvent;                       // int(1)  
    public $SoldOut;                         // int(1)  
    public $OtherAudience;                   // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Admissioninfogroup',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
