<?php
/**
 * Table Definition for user_has_permission
 * @package    UNL_UCBCN
 */

/**
 * Require DB_DataObject to extend from it.
 */
require_once 'DB/DataObject.php';
/**
 * ORM for a record within the database.
 * @package UNL_UCBCN
 */
class UNL_UCBCN_User_has_permission extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_has_permission';             // table name
    public $permission_id;                   // int(10)  not_null unsigned
    public $user_uid;                        // string(100)  not_null
    public $calendar_id;                     // int(10)  not_null unsigned
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_User_has_permission',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_excludeFromAutoRules = array('permission_id','user_uid','calendar_id');
    var $fb_fieldLabels = array('calendar_id'=>'Calendar');
    
    function insert()
    {
        $check = UNL_UCBCN::factory('user_has_permission');
        $check->permission_id = $this->permission_id;
        $check->user_uid = $this->user_uid;
        if (isset($this->calendar_id)) {
            $check->calendar_id = $this->calendar_id;
        } elseif (isset($_SESSION['calendar_id'])) {
            $check->calendar_id = $this->calendar_id = $_SESSION['calendar_id'];
        } else {
            return false;
        }
        if (!$check->find()) {
            return parent::insert();
        } else {
            return true;
        }
    }
    
}
