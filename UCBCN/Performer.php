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
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $performertype_id;                // int(11)  not_null
    public $event_id;                        // int(11)  not_null
    public $personalname;                    // string(100)  
    public $otherperformertype;              // string(255)  
    public $jobtitle;                        // string(100)  
    public $organizationname;                // string(100)  
    public $personalwebpageurl;              // blob(-1)  blob
    public $organizationwebpageurl;          // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Performer',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
