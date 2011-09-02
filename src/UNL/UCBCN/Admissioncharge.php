<?php
/**
 * Table Definition for admissioncharge
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
class UNL_UCBCN_Admissioncharge extends DB_DataObject
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $admissioninfogroup_id;           // int(10)  not_null unsigned
    public $price;                           // string(100)
    public $description;                     // string(255)

    public function getTable()
    {
        return 'admissioncharge';
    }

    function table()
    {
        return array(
            'id'=>129,
            'admissioninfogroup_id'=>129,
            'price'=>2,
            'description'=>2,
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
        return array('admissioninfogroup_id' => 'admissioninfo:id');
    }
}
