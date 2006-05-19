<?php
/**
 * Table Definition for document
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Document extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'document';                        // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $event_id;                        // int(11)  not_null
    public $name;                            // string(100)  
    public $url;                             // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Document',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
