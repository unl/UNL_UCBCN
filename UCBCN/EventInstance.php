<?php

/**
 * This class is a simple container object for all the details related to an event and its details.
 * 
 * 
 * @package UNL_UCBCN
 * @author Brett Bieber
 */
require_once 'UNL/UCBCN.php';

class UNL_UCBCN_EventInstance extends UNL_UCBCN
{
	/** UNL_UCBCN_Event object */
	var $event;
	/** UNL_UCBCN_Eventdatetime object */
	var $eventdatetime;
	
	/**
	 * constructor
	 * 
	 * @param mixed int|UNL_UCBCN_Eventdatetime The id for this record in the database, or the actual object.
	 */
	function __construct($edt)
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
	}
	
	/**
	 * This function returns the URL for this event instance.
	 */
	function getURL()
	{
		/*
		global $_UNL_UCBCN;
		if (isset($_UNL_UCBCN['defaultcalendar'])) {
			
		}
		*/
		$date = explode('-',$this->eventdatetime->starttime);
		$day = explode(' ',$date[2]);
		return UNL_UCBCN_Frontend::formatURL(array(	'y'=>$date[0],
														'm'=>$date[1],
														'd'=>$day[0],
														'id'=>$this->eventdatetime->id));
	}
}

?>