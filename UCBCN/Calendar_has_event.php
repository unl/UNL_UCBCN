<?php
/**
 * Table definition, and processing functions for calendar_has_event
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2007 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */

/**
 * Require DB_DataObject to extend from it.
 */
require_once 'DB/DataObject.php';

/**
 * Backend is required for UNL_UCBCN::factory
 */
require_once 'UNL/UCBCN.php';

/**
 * UNL_UCBCN_Subscription is needed to determine which subscribed calendars to update.
 */
require_once 'UNL/UCBCN/Subscription.php';

/**
 * ORM for a record within the database.
 * 
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2007 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_UCBCN_Calendar_has_event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar_has_event';              // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $calendar_id;                     // int(10)  not_null multiple_key unsigned
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $status;                          // string(100)  multiple_key
    public $source;                          // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Calendar_has_event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    /**
     * Performs an insert of a calendar_has_event record, and updates any subscribed
     * calenars.
     * 
     * @return int ID of the inserted record.
     */
    public function insert()
    {
        $this->datecreated     = date('Y-m-d H:i:s');
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidcreated     = $_SESSION['_authsession']['username'];
            $this->uidlastupdated = $_SESSION['_authsession']['username'];
        }
        $r = parent::insert();
        if ($r) {
            // Clean the cache on successful insert.
            UNL_UCBCN::cleanCache();
            if (self::processSubscriptions() && $this->status != 'pending') {
                $event = $this->getLink('event_id');
                if ($event->approvedforcirculation) {
                    UNL_UCBCN_Subscription::updateSubscribedCalendars($this->calendar_id, $this->event_id);
                }
            }
        }
        return $r;
    }
    
    /**
     * sets or gets the current status of processing subscriptions. This is used to
     * prevent other calendars from processing additional subscriptions while one is
     * in progress - a poor man's lock on the database.
     *
     * @param bool $status true or false
     * 
     * @return bool The status of processing subscriptions.
     */
    public function processSubscriptions($status = null)
    {
        global $_UNL_UCBCN;
        if (isset($status)) {
            $_UNL_UCBCN['process_subscriptions'] = (bool) $status;
        } else {
            if (isset($_UNL_UCBCN['process_subscriptions'])) {
                return $_UNL_UCBCN['process_subscriptions'];
            } else {
                $_UNL_UCBCN['process_subscriptions'] = true;
            }
        }
        return $_UNL_UCBCN['process_subscriptions'];
    }
    
    /**
     * Performs an update on this calendar_has_event record.
     * 
     * @return bool True on sucess
     */
    public function update()
    {
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidlastupdated = $_SESSION['_authsession']['username'];
        }
        $r = parent::update();
        if ($r) {
            // Clean the cache on successful update.
            UNL_UCBCN::cleanCache();
            if ($this->status != 'pending' && $this->status != 'archived') {
                $event = $this->getLink('event_id');
                if ($event->approvedforcirculation) {
                    UNL_UCBCN_Subscription::updateSubscribedCalendars($this->calendar_id, $this->event_id);
                }
            }
        }
        return $r;
    }
    
    /**
     * Returns bool false if the calendar does not have the event, 
     * otherwise returns status.
     *
     * @param UNL_UCBCN_Calendar $calendar Calendar to check.
     * @param UNL_UCBCN_Event    $event    Event to check.
     * 
     * @return string status, or bool false
     */
    public function calendarHasEvent(UNL_UCBCN_Calendar $calendar,UNL_UCBCN_Event $event)
    {
        $che              = UNL_UCBCN::factory('calendar_has_event');
        $che->calendar_id = $calendar->id;
        $che->event_id    = $event->id;
        if ($che->find()) {
            $che->fetch();
            return $che->status;
        } else {
            return false;
        }
    }
    
    /**
     * Removes this record - effectively removing this event from the calendar.
     * 
     * @return bool true on success.
     */
    public function delete()
    {
        $r = parent::delete();
        if ($r) {
            // Clean the cache on successful delete.
            UNL_UCBCN::cleanCache();
        }
        return $r;
    }
}
