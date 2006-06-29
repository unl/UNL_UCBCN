<?php

/**
 * This class is a simple container object for all the details related to an event and its details.
 * 
 * 
 * @pacakge UNL_UCBCN
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
	 * @param int $id eventdatetime.id  The id for this record in the database.
	 */
	function __construct($id)
	{
		$this->eventdatetime = UNL_UCBCN::factory('eventdatetime');
		if ($this->eventdatetime->get($id)) {
			$this->event = $this->eventdatetime->getLink('event_id');
		} else {
			return new UNL_UCBCN_Error('Could not find that event instance.');
		}
	}
}

?>