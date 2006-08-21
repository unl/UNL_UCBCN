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
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(255)  
    public $standard;                        // int(1)  
    public $sponsortype;                     // string(255)  
    public $webpageurl;                      // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Sponsor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_linkDisplayFields = array('name');
}
