<?php
/**
 * Table Definition for webcastlink
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Webcastlink extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'webcastlink';                     // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $webcast_id;                      // int(10)  not_null unsigned
    public $url;                             // blob(-1)  blob
    public $sequencenumber;                  // int(10)  unsigned
    public $related;                         // string(1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Webcastlink',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
