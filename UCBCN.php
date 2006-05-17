<?php
/**
 * This is a skeleton PEAR package attempt for the UC Berkeley Calendar Schema.
 * 
 * @author bbieber
 * @package UNL_UCBCN
 */
require_once 'DBDataObjects/config.inc.php';

class UNL_UCBCN
{
	
	var $template;
	
	function __construct()
	{
		$this->template = 'default';
	}
	
	function showNavigation()
	{
		echo	'<ul>' .
				'<li><a href="?action=showEvent">Show Event</a></li>'.
				'<li><a href="?action=showEventSubmitForm">Show Event Submit Form</a></li>'.
				'<li><a href="?action=showCalendar">Show Calendar of Events</a></li>'.
				'</ul>';
	}
	
	function run($action='')
	{
		if (empty($action) && isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		switch($action)
		{
			case 'showEvent':
				$this->showEvent(1);
			break;
			case 'showEventSubmitForm':
				$this->showEventSubmitForm();
			break;
			default:
			case 'showCalendar':
				$this->showCalendar();
			break;
		}
	}
	
	function showCalendar($date='')
	{
		require 'Date.php';
		if (empty($date)) {
			$date = time();
		}
		$d = new Date_Calc();
		$savant = $this->getSavant();
		for ($i=1;$i<=$d->daysInMonth(date('n',$date));$i++) {
			$savant->days[$i] = $this->getEventList(date('Y-m-').$i);
		}
		$savant->display('showCalendar.php');
	}
	
	function getEventList($date='')
	{
		$list = '';
		$events = DB_DataObject::factory('event');
		$events->eventDate = $date;
		if ($events->find()) {
			while ($events->fetch()) {
				$list .= $events->strTitle;
			}
		} else {
			$list .= 'No events';
		}
		return $list;
	}
	
	function showEventSubmitForm()
	{
		require_once 'DB/DataObject/FormBuilder.php';
		$savant = $this->getSavant();
		$events = DB_DataObject::factory('event');
		$fb = DB_DataObject_FormBuilder::create($events);
		$savant->form = $fb->getForm();
		$savant->display('showEventSubmitForm.php');
	}
	
	function showEvent($id)
	{
		$event = DB_DataObject::factory('event');
		if ($event->get($id)) {
			$savant = $this->getSavant();
			$event->getLinks();
			$savant->event =& $event;
			$savant->display('showEvent.php');
		} else {
			$this->showError('The event with id of '.$id.' was not found.');
		}
	}
	
	function showError($description)
	{
		$savant = $this->getSavant();
		$savant->description = $description;
		$savant->display('showError.php');
	}
	
	function getSavant()
	{
		require_once 'Savant3.php';
		return new Savant3(array(
            'template_path' => '/Users/bbieber/Documents/workspace/UNL_UCBCN/templates/' . $this->template,
        ));
	}
}
?>
