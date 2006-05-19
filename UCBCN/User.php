<?php
/**
 * Table Definition for user
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_User extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user';                            // table name
    public $calnetuid;                       // string(100)  not_null
    public $account_id;                      // int(11)  not_null
    public $accountstatus;                   // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $calnetuidcreated;                // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $calnetuidlastupdated;            // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_User',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
