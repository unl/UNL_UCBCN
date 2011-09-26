<?php
/**
 * Table Definition for user_has_permission
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

/**
 * ORM for a record within the database.
 *
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_User_Permissions extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $permission_id;                   // int(10)  not_null unsigned
    public $user_uid;                        // string(100)  not_null
    public $calendar_id;                     // int(10)  not_null unsigned

    public function getTable()
    {
        return 'user_has_permission';
    }

    function table()
    {
        return array(
            'id'=>129,
            'permission_id'=>129,
            'user_uid'=>130,
            'calendar_id'=>129,
        );
    }

    function keys()
    {
        return array(
            'id',
        );
    }
    
    function sequenceKey()
    {
        return array('id',true);
    }
    
    function links()
    {
        return array('permission_id' => 'permission:id',
                     'user_uid'      => 'user:uid',
                     'calendar_id'   => 'calendar:id');
    }
    
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
