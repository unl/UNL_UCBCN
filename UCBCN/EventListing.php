<?php
require_once 'UNL/UCBCN.php';

/**
 * This class holds all the events for the list.
 * 
 */
class UNL_UCBCN_EventListing
{
	var $status;
	// Array of UNL_UCBCN_Event or UNL_UCBCN_EventInstance objects for this listing.
	var $events = array();
	
	/**
	 * Constructor
	 *
	 * @param string $type Optional parameter to fetch an event listing for types of events.
	 * @param array $options options for the specific constructor to initialize the object.
	 */
	function __construct($type=NULL,$options=array())
	{
	    
		switch($type) {
		    case 'day':
		        $this->constructDayEventInstanceList($options);
	        break;
		    default:
		    break;
		}
	}

	/**
	 * Populates events with a listing of events for the calendar given.
	 *
	 * @param array $options Associative array of options
	 * 		'year'		int		Year of the events
	 * 		'month'		int		Month
	 * 		'day'		int		Day
	 * 		'calendar'	UNL_UCBCN_Calendar	Calendar to fetch events for (optional).
	 * 		'orderby'	string	ORDER BY sql clause.
	 * 		
	 */
	function constructDayEventInstanceList($options)
	{
	    require_once 'Calendar/Day.php';
		$day = new Calendar_Day($options['year'],$options['month'],$options['day']);
		$eventdatetime = UNL_UCBCN::factory('eventdatetime');
		if (isset($options['orderby'])) {
		    $orderby = 	$options['orderby'];
		} else {
		    $orderby = 'eventdatetime.starttime ASC';
		}
		if (isset($options['calendar'])) {
		    $calendar =& $options['calendar'];
			$eventdatetime->query('SELECT DISTINCT eventdatetime.* FROM event,calendar_has_event,eventdatetime ' .
							'WHERE calendar_has_event.calendar_id='.$calendar->id.' ' .
									'AND (calendar_has_event.status =\'posted\' OR calendar_has_event.status =\'archived\') '.
									'AND calendar_has_event.event_id = eventdatetime.event_id ' .
									'AND eventdatetime.starttime LIKE \''.date('Y-m-d',$day->getTimestamp()).'%\' ' .
							'ORDER BY '.$orderby);
		} else {
		    $calendar = NULL;
			$eventdatetime->whereAdd('starttime LIKE \''.date('Y-m-d',$day->getTimestamp()).'%\'');
			$eventdatetime->orderBy($orderby);
			$eventdatetime->find();
		}
		while ($eventdatetime->fetch()) {
			// Populate the events to display.
			$this->events[] = new UNL_UCBCN_EventInstance($eventdatetime,$calendar);
		}
	}
	
}
