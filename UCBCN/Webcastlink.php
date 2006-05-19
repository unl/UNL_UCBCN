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
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $webcast_id;                      // int(11)  not_null
    public $url;                             // blob(-1)  blob
    public $sequencenumber;                  // int(11)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Webcastlink',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
