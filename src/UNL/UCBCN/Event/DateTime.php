<?php
/**
 * Table Definition for eventdatetime
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
class UNL_UCBCN_Event_DateTime extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $location_id;                     // int(10)  not_null multiple_key unsigned
    public $starttime;                       // datetime(19)  multiple_key binary
    public $endtime;                         // datetime(19)  multiple_key binary
    public $recurringtype;                   // string(255)
    public $recurs_until;                    // datetime
    public $rectypemonth;                    // string(255)
    public $room;                            // string(255)
    public $hours;                           // string(255)
    public $directions;                      // blob(4294967295)  blob
    public $additionalpublicinfo;            // blob(4294967295)  blob

    public function getTable()
    {
        return 'eventdatetime';
    }

    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'location_id'=>129,
            'starttime'=>14,
            'endtime'=>14,
            'recurringtype'=>2,
            'recurs_until'=>14,
            'rectypemonth'=>2,
            'room'=>2,
            'hours'=>2,
            'directions'=>66,
            'additionalpublicinfo'=>66,
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
        return array('event_id'    => 'event:id',
                     'location_id' => 'location:id');
    }

    public function insert()
    {
        $r = parent::insert();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
        return $r;
    }
    
    public function update()
    {
        $r = parent::update();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
        //update a facebook event.
        $facebook = new UNL_UCBCN_Facebook_Instance($this->id);
        $facebook->updateEvent();
        return $r;
    }
    
    public function delete()
    {
        //delete the facebook event.
        if ($this->id != null) {
            $facebook = new UNL_UCBCN_Facebook_Instance($this->id);
            $facebook->deleteEvent();
        }
        //delete the actual event.
        $r = parent::delete();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
        return $r;
    }
    
    /**
     * Gets an object for the location of this event date and time.
     *
     * @return UNL_UCBCN_Location
     */
    public function getLocation()
    {
        if (isset($this->location_id)) {
            return $this->getLink('location_id');
        } else {
            return false;
        }
    }
}
