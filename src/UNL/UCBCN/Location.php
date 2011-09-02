<?php
/**
 * Details for locations within the database.
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
 * ORM for a Location record within the database.
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Location extends DB_DataObject
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(100)  multiple_key
    public $streetaddress1;                  // string(255)
    public $streetaddress2;                  // string(255)
    public $room;                            // string(100)
    public $city;                            // string(100)
    public $state;                           // string(2)
    public $zip;                             // string(10)
    public $mapurl;                          // blob(4294967295)  blob
    public $webpageurl;                      // blob(4294967295)  blob
    public $hours;                           // string(255)
    public $directions;                      // blob(4294967295)  blob
    public $additionalpublicinfo;            // string(255)
    public $type;                            // string(100)
    public $phone;                           // string(50)
    public $standard;                        // int(1)

    public function getTable()
    {
        return 'location';
    }

    function table()
    {
        return array(
            'id'=>129,
            'name'=>2,
            'streetaddress1'=>2,
            'streetaddress2'=>2,
            'room'=>2,
            'city'=>2,
            'state'=>2,
            'zip'=>2,
            'mapurl'=>66,
            'webpageurl'=>66,
            'hours'=>2,
            'directions'=>66,
            'additionalpublicinfo'=>2,
            'type'=>2,
            'phone'=>2,
            'standard'=>17,
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
    
}
