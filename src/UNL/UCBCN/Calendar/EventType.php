<?php
namespace UNL\UCBCN\Calendar;

use UNL\UCBCN\ActiveRecord\Record;
/**
 * Table Definition for eventtype
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
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class EventType extends Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $calendar_id;                     // int(10)  not_null unsigned
    public $name;                            // string(100)  not_null multiple_key
    public $description;                     // string(255)
    public $eventtypegroup;                  // string(8)
    public $standard;                        // int(1)

    public static function getTable()
    {
        return 'eventtype';
    }

    function table()
    {
        return array(
            'id'=>129,
            'calendar_id'=>129,
            'name'=>130,
            'description'=>2,
            'eventtypegroup'=>2,
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
    
    function links()
    {
        return array('calendar_id' => 'calendar:id');
    }
    
}
