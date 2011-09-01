<?php
/**
 * This class is a simple container object for all the details related to an event
 * and its details.
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
 * Requires the UNL_UCBCN backend class.
 */
require_once 'UNL/UCBCN.php';

/**
 * Generic object to hold a single event, and a single event date time.
 * Together, the two represent an instance of an event at a location.
 * 
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_EventInstance extends UNL_UCBCN
{
    /**
     * @var UNL_UCBCN_Event Event details
     */
    public $event;
    
    /**
     * @var UNL_UCBCN_Eventdatetime Date and time details
     */
    public $eventdatetime;
    
    /**
     * @var UNL_UCBCN_Calendar Optional calendar associated with this instance.
     */
    public $calendar;
    
    /**
     * @var string URL to this event instance (with html entities).
     */
    public $url;
    
    public $facebook;
    
    public $facebookRSVP;
    /**
     * constructor
     * 
     * @param int|UNL_UCBCN_Eventdatetime $edt      eventdatetime.id in the database, or the actual object.
     * @param int|UNL_UCBCN_Calendar      $calendar optional parameter for the calendar this event is for.
     */
    function __construct($edt, $calendar=null)
    {
        if (is_object($edt) && get_class($edt)=='UNL_UCBCN_Eventdatetime') {
            $this->eventdatetime = clone($edt);
            $this->event         = $this->eventdatetime->getLink('event_id');
        } else {
            $this->eventdatetime = UNL_UCBCN::factory('eventdatetime');
            if ($this->eventdatetime->get($edt)) {
                $this->event = $this->eventdatetime->getLink('event_id');
            } else {
                throw new UNL_UCBCN_UnexpectedValueException('Could not find that event instance.');
            }
        }
        if (isset($calendar)) {
            if (is_object($calendar) && get_class($calendar)=='UNL_UCBCN_Calendar') {
                $this->calendar = clone($calendar);
            } else {
                $c = UNL_UCBCN::factory('calendar');
                if ($c->get($calendar)) {
                    $this->calendar = $c;
                }
            }
        }
        $this->url = $this->getURL();
        $this->facebook = new UNL_UCBCN_FacebookInstance($this->eventdatetime->id);
        $this->facebookRSVP = new UNL_UCBCN_FacebookRSVP($this->eventdatetime->id, $this->calendar->id);
    }
    
    /**
     * This function returns the URL for this event instance.
     * 
     * @return string URL to this event instance.
     */
    function getURL()
    {
        $date = explode('-', $this->eventdatetime->starttime);
        $day  = explode(' ', $date[2]);
        $f    = array('y'=>$date[0],
                      'm'=>$date[1],
                      'd'=>$day[0],
                      'eventdatetime_id'=>$this->eventdatetime->id);
        if (isset($this->calendar)) {
            $f['calendar'] = $this->calendar->id;
        }
        return UNL_UCBCN_Frontend::formatURL($f);
    }
    
    public function fixRecurringEvent(&$event, $startdate, $rec_id=0, $isongoing=false)
    {
        // Update URL
        $date = explode('-', $startdate);
        $day  = explode(' ', $date[2]);
        $f    = array('y'=>$date[0],
                      'm'=>$date[1],
                      'd'=>$day[0],
                      'eventdatetime_id'=>$event->eventdatetime->id,
                      'event_id'=>$event->eventdatetime->event_id);
        if (isset($event->calendar)) {
            $f['calendar'] = $event->calendar->id;
        }
        $event->url = UNL_UCBCN_Frontend::formatURL($f);
        // Update starttime and endtime
        $starttime =& $event->eventdatetime->starttime;
        $original = strtotime($starttime);
        if ($isongoing) {
            // Get the real startdate
            $id = $event->eventdatetime->event_id;
            $db = $event->eventdatetime->getDatabaseConnection();
            $sql = 'SELECT recurringdate FROM recurringdate WHERE event_id='.$id.' 
            		AND recurrence_id='.$rec_id.' AND ongoing=FALSE;';
            $res = $db->query($sql);
            $startdate = $res->fetchOne();
        }
        $starttime = $startdate.substr($starttime, 10);
        // get difference between now and the original starttime
        $updated = strtotime($starttime);
        $updated -= $original;
        // add the difference to endtime
        $endtime =& $event->eventdatetime->endtime;
        $original = strtotime($endtime);
        $updated += $original;
        $endtime = date('Y-m-d H:i:s', $updated);
    }
}

?>