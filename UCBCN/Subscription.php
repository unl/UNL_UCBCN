<?php
/**
 * Table Definition for subscription
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Subscription extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'subscription';                    // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $user_uid;                        // string(255)  not_null
    public $name;                            // string(100)  
    public $automaticapproval;               // int(1)  not_null
    public $timeperiod;                      // date(10)  binary
    public $expirationdate;                  // date(10)  binary
    public $searchcriteria;                  // blob(-1)  blob
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Subscription',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
