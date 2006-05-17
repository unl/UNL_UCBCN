<?php
/**
 * Table Definition for performer
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Performer extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'performer';                       // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $PerformerType_ID;                // int(10)  not_null multiple_key unsigned
    public $Event_ID;                        // int(10)  not_null multiple_key unsigned
    public $PersonalName;                    // string(100)  
    public $OtherPerformerType;              // string(255)  
    public $JobTitle;                        // string(100)  
    public $OrganizationName;                // string(100)  
    public $PersonalWebPageURL;              // blob(65535)  blob
    public $OrganizationWebPageURL;          // blob(65535)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Performer',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
