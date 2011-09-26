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
class UNL_UCBCN_Eventdatetime extends UNL_UCBCN_Record
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

    public function _array2date($dateInput, $timestamp = false)
    {
        if (isset($dateInput['M'])) {
            $month = $dateInput['M'];
        } elseif (isset($dateInput['m'])) {
            $month = $dateInput['m'];
        } elseif (isset($dateInput['F'])) {
            $month = $dateInput['F'];
        }
        if (isset($dateInput['Y'])) {
            $year = $dateInput['Y'];
        } elseif (isset($dateInput['y'])) {
            $year = $dateInput['y'];
        }
        if (isset($dateInput['H'])) {
            $hour = $dateInput['H'];
        } elseif (isset($dateInput['h']) || isset($dateInput['g'])) {
            if (isset($dateInput['h'])) {
                $hour = $dateInput['h'];
            } elseif (isset($dateInput['g'])) {
                $hour = $dateInput['g'];
            }
            if (isset($dateInput['a'])) {
                $ampm = $dateInput['a'];
            } elseif (isset($dateInput['A'])) {
                $ampm = $dateInput['A'];
            }
            if (strtolower(preg_replace('/[\.\s,]/', '', $ampm)) == 'pm') {
                if ($hour != '12') {
                    $hour += 12;
                    if ($hour == 24) {
                        $hour = '';
                        ++$dateInput['d'];
                    }
                }
            } else {
                if ($hour == '12') {
                    $hour = '00';
                }
            }
        }
        $strDate = '';
        if (isset($year) || isset($month) || isset($dateInput['d'])) {
            if (isset($year) && ($len = strlen($year)) > 0) {
                if ($len < 2) {
                    $year = '0'.$year;
                }
                if ($len < 4) {
                    $year = substr(date('Y'), 0, 2).$year;
                }
            } else {
                $year = '0000';
            }
            if (isset($month) && ($len = strlen($month)) > 0) {
                if ($len < 2) {
                    $month = '0'.$month;
                }
            } else {
                $month = '00';
            }
            if (isset($dateInput['d']) && ($len = strlen($dateInput['d'])) > 0) {
                if ($len < 2) {
                    $dateInput['d'] = '0'.$dateInput['d'];
                }
            } else {
                $dateInput['d'] = '00';
            }
            $strDate .= $year.'-'.$month.'-'.$dateInput['d'];
        }
        if (isset($hour) || isset($dateInput['i']) || isset($dateInput['s'])) {
            if (isset($hour) && ($len = strlen($hour)) > 0) {
                if ($len < 2) {
                    $hour = '0'.$hour;
                }
            } else {
                $hour = '00';
            }
            if (isset($dateInput['i']) && ($len = strlen($dateInput['i'])) > 0) {
                if ($len < 2) {
                    $dateInput['i'] = '0'.$dateInput['i'];
                }
            } else {
                $dateInput['i'] = '00';
            }
            if (!empty($strDate)) {
                $strDate .= ' ';
            }
            $strDate .= $hour.':'.$dateInput['i'];
            if (isset($dateInput['s']) && ($len = strlen($dateInput['s'])) > 0) {
                $strDate .= ':'.($len < 2 ? '0' : '').$dateInput['s'];
            }
        }
        return $strDate;
    }
    
    public function dateToDatabaseCallback($value)
    {
        return $value;
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
