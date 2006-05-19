<?php
/**
 * Table Definition for webcast
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Webcast extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'webcast';                         // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $event_id;                        // int(11)  not_null
    public $title;                           // string(100)  
    public $status;                          // string(100)  
    public $dateavailable;                   // datetime(19)  binary
    public $playertype;                      // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Webcast',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
