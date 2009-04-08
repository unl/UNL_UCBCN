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
                return new UNL_UCBCN_Error('Could not find that event instance.');
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
}

?>