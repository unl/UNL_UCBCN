<?php
namespace UNL\UCBCN\Event;

use UNL\UCBCN\ActiveRecord\Record;
/**
 * Table Definition for event_has_sponsor
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
class Sponsor extends Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $sponsor_id;                      // int(10)  not_null multiple_key unsigned

    public static function getTable()
    {
        return 'event_has_sponsor';
    }

    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'sponsor_id'=>129,
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
        return array('event_id'   => 'event:id',
                     'sponsor_id' => 'sponsor:id');
    }

}
