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
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $name;                            // string(255)  
    public $level;                           // string(100)  
    public $logotitle;                       // string(100)  
    public $logourl;                         // blob(-1)  blob
    public $description;                     // blob(-1)  blob
    public $webpageurl;                      // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Sponsor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
