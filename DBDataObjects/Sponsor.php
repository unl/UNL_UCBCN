<?php
/**
 * Table Definition for sponsor
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Sponsor extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'sponsor';                         // table name
    public $ID;                              // int(10)  not_null primary_key unsigned auto_increment
    public $Name;                            // string(255)  
    public $Level;                           // string(100)  
    public $LogoTitle;                       // string(100)  
    public $LogoURL;                         // blob(65535)  blob
    public $Description;                     // blob(65535)  blob
    public $WebPageURL;                      // blob(65535)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Sponsor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
