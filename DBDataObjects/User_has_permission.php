<?php
/**
 * Table Definition for user_has_permission
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_User_has_permission extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_has_permission';             // table name
    public $Permission_ID;                   // int(10)  not_null primary_key multiple_key unsigned
    public $User_CalNetUID;                  // string(100)  not_null primary_key multiple_key
    public $Accoun;                          // int(10)  unsigned

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_User_has_permission',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
