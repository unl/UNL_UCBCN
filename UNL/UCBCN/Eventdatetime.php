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
 * Require DB_DataObject to extend from it.
 */
require_once 'DB/DataObject.php';

/**
 * ORM for a record within the database.
 *
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Eventdatetime extends DB_DataObject
{
	###START_AUTOCODE
	/* the code below is auto generated do not remove the above tag */

	public $__table = 'eventdatetime';                   // table name
	public $id;                              // int(10)  not_null primary_key unsigned auto_increment
	public $event_id;                        // int(10)  not_null multiple_key unsigned
	public $location_id;                     // int(10)  not_null multiple_key unsigned
	public $starttime;                       // datetime(19)  multiple_key binary
	public $endtime;                         // datetime(19)  multiple_key binary
	public $room;                            // string(255)
	public $hours;                           // string(255)
	public $directions;                      // blob(4294967295)  blob
	public $additionalpublicinfo;            // blob(4294967295)  blob

	/* Static get */
	function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Eventdatetime',$k,$v); }

	/* the code above is auto generated do not remove the tag below */
	###END_AUTOCODE

	function table()
	{
		return array(
            'id'=>129,
            'event_id'=>129,
            'location_id'=>129,
            'starttime'=>14,
            'endtime'=>14,
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

	public function preProcessForm(&$values, &$fb)
	{

		// Capture event_id foreign key if needed.
		if (isset($GLOBALS['event_id'])) {
			$values['event_id'] = $GLOBALS['event_id'];
		}
		if (isset($values['starthour'])) {
			//Assume today if starttime is empty
			if (empty($values['starttime'])) {
				$values['starttime'] = date('Y-m-d');
			}
			$starttime = date('Y-m-d', strtotime($values['starttime']));
			$values['starttime'] = $starttime.' '.$this->_array2date($values['starthour']);
		}
		if (isset($values['endhour'])) {
			if (empty($values['endtime'])) {
				$values['endtime'] = $starttime;
			}
			$values['endtime'] = $values['endtime'].' '.$this->_array2date($values['endhour']);
		}
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
			if(isset($month) && ($len = strlen($month)) > 0) {
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

	public function prepareLinkedDataObject(&$linkedDataObject, $field) {
		//you may want to include one or both of these
		if ($linkedDataObject->tableName() == 'location') {
			if (isset($this->location_id)) {
				$linkedDataObject->whereAdd('standard=1 OR id='.$this->location_id);
			} else {
				$linkedDataObject->standard = 1;
			}
		}
	}

	public function insert()
	{
		$r = parent::insert();
		if ($r) {
			UNL_UCBCN::cleanCache();
		}
		return $r;
	}

	public function update()
	{
		$r = parent::update();
		if ($r) {
			UNL_UCBCN::cleanCache();
		}
		return $r;
	}

	public function delete()
	{
		$r = parent::delete();
		if ($r) {
			UNL_UCBCN::cleanCache();
		}
		return $r;
	}

	/**
	 * Gets an object for the location of this event date and time.
	 *
	 * @return UNL_UCBCN_Location
	 */
	public function getLocation($id = null)
	{
		if ($id){
			$location = UNL_UCBCN::factory('location');
			$location->id = $id;
			if($location->find()){
				$location->fetch();
				return $location;
			} else {
				return new UNL_UCBCN_Error('Error, the location with that id was not found!');
			}
		} else if (isset($this->location_id)) {
			return $this->getLink('location_id');
		} else {
			return false;
		}
	}
	
	/**
	 * Get all standard locations
	 *
	 * @return UNL_UCBCN_Location
	 */
	public function getLocations(){
		$location = UNL_UCBCN::factory('location');
		$location->standard = 1;
		$location->orderBy('name ASC');
		$location->find();
		return $location;
	}
}
