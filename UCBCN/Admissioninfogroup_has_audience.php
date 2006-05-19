<?php
/**
 * Table Definition for admissioninfogroup_has_audience
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Admissioninfogroup_has_audience extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'admissioninfogroup_has_audience';    // table name
    public $admissioninfogroup_id;           // int(11)  not_null
    public $audience_id;                     // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Admissioninfogroup_has_audience',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
