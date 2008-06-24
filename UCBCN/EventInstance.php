<?php
/**
 * This class is a simple container object for all the details related to an event and its details.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
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
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_EventInstance extends UNL_UCBCN
{
	/** UNL_UCBCN_Event object */
	var $event;
	/** UNL_UCBCN_Eventdatetime object */
	var $eventdatetime;
	/** Optional calendar associated with this event instance */
	var $calendar;	
	/** URL to this event instance */
	var $url;
	
	/**
	 * constructor
	 * 
	 * @param mixed int|UNL_UCBCN_Eventdatetime The id for this record in the database, or the actual object.
	 * @param mixed int|UNL_UCBCN_Calendar... optional parameter for the calendar this event is for.
	 */
	function __construct($edt,$calendar=NULL)
	{
		if (is_object($edt) && get_class($edt)=='UNL_UCBCN_Eventdatetime') {
			$this->eventdatetime = clone($edt);
			$this->event = $this->eventdatetime->getLink('event_id');
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
	 * @return string URL to this event instance.
	 */
	function getURL()
	{
		$date = explode('-',$this->eventdatetime->starttime);
		$day = explode(' ',$date[2]);
		$f = array(	'y'=>$date[0],
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