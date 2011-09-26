<?php
/**
 * Table Definition for subscription
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

require_once 'UNL/UCBCN/Calendar_has_event.php';

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
class UNL_UCBCN_Subscription extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $calendar_id;                     // int(10)  not_null multiple_key unsigned
    public $name;                            // string(100)
    public $automaticapproval;               // int(1)  not_null
    public $timeperiod;                      // date(10)  binary
    public $expirationdate;                  // date(10)  binary
    public $searchcriteria;                  // blob(4294967295)  blob
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)

    public function getTable()
    {
        return 'subscription';
    }

    function table()
    {
        return array(
            'id'=>129,
            'calendar_id'=>129,
            'name'=>2,
            'automaticapproval'=>145,
            'timeperiod'=>6,
            'expirationdate'=>6,
            'searchcriteria'=>66,
            'datecreated'=>14,
            'uidcreated'=>2,
            'datelastupdated'=>14,
            'uidlastupdated'=>2,
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
        return array('calendar_id'    => 'calendar:id',
                     'uidcreated'     => 'user:uid',
                     'uidlastupdated' => 'user:uid');
    }

    /**
     * Translates search criteria into calendars.
     *
     * @param string $searchcriteria Array of calendars to check.
     *
     * @return array Array of the calendars which match this criteria.
     */
    public function getCalendars($searchcriteria)
    {
        $searchcriteria = explode('=', $searchcriteria);
        $cals           = array();
        foreach ($searchcriteria as $c) {
            $calids = array();
            if (preg_match('/[^\d]*([\d]+)[^\d]*/', $c, $calids)) {
                if ($calids[1] != 0) {
                    $cals[] = intval($calids[1]);
                }
            }
        }
        return $cals;
    }

    /**
     * Inserts a record into the subscription table, and processes the subscription
     * for matching events.
     *
     * @return int ID of inserted record on success.
     */
    public function insert()
    {
        global $_UNL_UCBCN;
        $this->datecreated     = date('Y-m-d H:i:s');
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidcreated     = $_SESSION['_authsession']['username'];
            $this->uidlastupdated = $_SESSION['_authsession']['username'];
        }
        $result = parent::insert();
        if ($result) {
            // If insert was successful, process the subscription immediately if the user chose so.
            if ($this->process()) {
                // Events were added to the current calendar.
            }
        }
        return $result;
    }
    
    /**
     * Performs an update on this subscription. This will re-evaluate all the events
     * to see if they match the subscription and add them in.
     *
     * Calls UNL_UCBCN_process() if update was successful.
     *
     * @param object $do Dataobject
     *
     * @return bool true on success
     */
    public function update($do=false)
    {
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidlastupdated = $_SESSION['_authsession']['username'];
        }
        $result = parent::update();
        if ($result) {
            // If insert was successful, process the subscription immediately if the user chose so.
            if ($this->process()) {
                // Events were added to the current calendar.
            }
        }
        return $result;
    }
    
    /**
     * Processes this subscription and adds events not currently
     * added to the calendar this subscription is for.
     *
     * @param int $event_id Optionally only add the event with the mathcing id.
     *
     * @return int number of events added to the calendar
     */
    public function process($event_id = null)
    {
        $added = 0;
        if (isset($this->id) && isset($this->calendar_id)) {
            $res =& $this->matchingEvents(true, $event_id);
            if ($res->numRows()) {
                // There are events to insert, postpone any subscription processing until we're done.
                $process_subscriptions = UNL_UCBCN_Calendar_Event::processSubscriptions();
                UNL_UCBCN_Calendar_Event::processSubscriptions(false);
                $calendar = $this->getLink('calendar_id');
                $user     = $this->getLink('uidcreated');
                $status   = $this->getApprovalStatus();
                while ($row = $res->fetchRow()) {
                    $e = UNL_UCBCN::factory('event');
                    if ($e->get($row[0]) && $calendar !== false) {
                        $calendar->addEvent($e, $status, $user, 'subscription');
                        $added++;
                    }
                }
                // restore process subscriptions to what it was before.
                UNL_UCBCN_Calendar_Event::processSubscriptions($process_subscriptions);
                self::updateSubscribedCalendars($this->calendar_id, $event_id);
            }
        }
        return $added;
    }
    
    /**
     * returns the string equivalent of the automatic approval status, for
     * inserting into the calendar_has_event database.
     * It will return 'posted' if automatic approval is true.
     * 'pending' otherwise.
     *
     * @return string the Status.
     */
    public function getApprovalStatus()
    {
        if ($this->automaticapproval==1) {
            return 'posted';
        } else {
            return 'pending';
        }
    }
    
    /**
     * Finds the events matching this subscription.
     *
     * @param bool $exclude_existing If existing events on this calendar should be excluded.
     * @param int  $event_id         Optional parameter for checking an individual event.
     *
     * @return MDB2_Result
     */
    public function matchingEvents($exclude_existing = true, $event_id = null)
    {
        $mdb2 =& $this->getDatabaseConnection();
        $sql  = 'SELECT DISTINCT event.id FROM event,calendar_has_event WHERE calendar_has_event.event_id = event.id
                 AND ('.$this->searchcriteria.') AND calendar_has_event.status != \'pending\' AND event.approvedforcirculation = 1';
        if ($exclude_existing) {
            $sql .= ' AND event.id NOT IN (SELECT DISTINCT event.id FROM event, calendar_has_event AS c2 WHERE c2.calendar_id ='.$this->calendar_id.' AND c2.event_id = event.id)';
        }
        if (isset($event_id)) {
            $sql .= ' AND event.id = '.$event_id;
        }
        $res =& $mdb2->query($sql);
        return $res;
    }
    
    /**
     * This function is called when a calendar has just had an event added. Called
     * from UNL_UCBCN_Calendar_Event->insert();
     *
     * @param int $calendar_id The primary key of the calendar which was updated.
     * @param int $event_id    Optionally, the id of the event to add.
     *
     * @return int Number of calenars updated (if any).
     */
    public function updateSubscribedCalendars($calendar_id, $event_id = null)
    {
        $updated       = 0;
        $subscriptions = UNL_UCBCN::factory('subscription');
        $subscriptions->whereAdd('searchcriteria LIKE \'%calendar_has_event.calendar_id='.$calendar_id.' %\'');
        if ($subscriptions->find()) {
            while ($subscriptions->fetch()) {
                if ($subscriptions->process($event_id)) {
                    // Events were added.
                    $updated++;
                }
            }
        }
        return $updated;
    }
    
    /**
     * Checks if a calendar has subscribers.
     *
     * @param int $calendar_id The id of the calendar to check.
     *
     * @return int
     */
    public function calendarHasSubscribers($calendar_id)
    {
        $subscriptions = UNL_UCBCN::factory('subscription');
        $subscriptions->whereAdd('searchcriteria LIKE \'%calendar_has_event.calendar_id='.$calendar_id.' %\'');
        return $subscriptions->find();
    }
}
