<?php
/**
 * Table Definition for eventdatetime
 * @package    UNL_UCBCN
 */

/**
 * Require DB_DataObject to extend from it.
 */
require_once 'DB/DataObject.php';

/**
 * ORM for a record within the database.
 * @package UNL_UCBCN
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
    public $directions;                      // blob(-1)  blob
    public $additionalpublicinfo;            // blob(-1)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Eventdatetime',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_fieldLabels			= array('location_id'			=> 'Location',
    										'starttime'				=> 'Start Time',
    										'endtime'				=> 'End Time',
    										'hours'					=> 'Building Hours',
    										'additionalpublicinfo'	=> 'Additional Public Info');
    var $fb_elementTypeMap          = array('datetime'=>'jscalendar');
    var $fb_hiddenFields			= array('event_id');
    var $fb_excludeFromAutoRules	= array('event_id');
    var $fb_linkNewValue			= true;
    var $fb_addFormHeader			= false;
    var $fb_formHeaderText			= 'Event Location, Date and Time';
    var $fb_dateToDatabaseCallback  = array('UNL_UCBCN_Eventdatetime','dateToDatabaseCallback');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
	    $options = array(
		    'baseURL' => './templates/default/jscalendar/',
		    'styleCss' => 'calendar.css',
		    'language' => 'en',
		    'image' => array(
		        'src' => './templates/default/jscalendar/cal.gif',
		        'border' => 0
		    ),
		    'setup' => array(
		        'inputField' => $fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix,
		        'ifFormat' => '%Y-%m-%d',
		        'weekNumbers' => false,
		        'showOthers' => true
		    )
		);
		$dateoptions = array('format'=>'g i A',
		                    'optionIncrement'=>array('i'=>5),
		                    'addEmptyOption'=>true);
		$this->fb_preDefElements['starttime'] = new HTML_QuickForm_group('starttime_group','Start Time:',
		    array(
		        HTML_QuickForm::createElement('text', $fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix, null, array('id'=>$fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix, 'size'=>10)),
		        HTML_QuickForm::createElement('jscalendar', 'date1_calendar', null, $options),
		        HTML_QuickForm::createElement('date',$fb->elementNamePrefix.'starthour'.$fb->elementNamePostfix,null, $dateoptions)
		    ), null, false);
		$options['setup']['inputField'] = $fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix;
    	$this->fb_preDefElements['endtime'] = new HTML_QuickForm_group('endtime_group','End Time:',
		    array(
		        HTML_QuickForm::createElement('text', $fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix, null, array('id'=>$fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix, 'size'=>10)),
		        HTML_QuickForm::createElement('jscalendar', 'date2_calendar', null, $options),
		        HTML_QuickForm::createElement('date',$fb->elementNamePrefix.'endhour'.$fb->elementNamePostfix,null, $dateoptions)
		    ), null, false);
    }
    
    function postGenerateForm(&$form, &$fb)
    {	
		if (isset($this->starttime)) {
            $form->setDefaults(array('starttime'=>substr($this->starttime,0,10)));
            if (substr($this->starttime,11) != '00:00:00') {
                $form->setDefaults(array($fb->elementNamePrefix.'starthour'.$fb->elementNamePostfix=>substr($this->starttime,11)));
            }
        }
        if (isset($this->endtime)) {
            $form->setDefaults(array('endtime'=>substr($this->endtime,0,10)));
            if (substr($this->endtime,11) != '00:00:00') {
                $form->setDefaults(array($fb->elementNamePrefix.'endhour'.$fb->elementNamePostfix=>substr($this->endtime,11)));
            }
        }
    }
    
    function preProcessForm(&$values, &$fb)
    {
    	// Capture event_id foreign key if needed.
    	if (isset($GLOBALS['event_id'])) {
    		$values['event_id'] = $GLOBALS['event_id'];
    	}
    	if (isset($values['starthour'])) {
    	   $values['starttime'] = $values['starttime'].' '.$this->_array2date($values['starthour']);
    	}
    	if (isset($values['endhour'])) {
    	   $values['endtime'] = $values['endtime'].' '.$this->_array2date($values['endhour']);
    	}
    }
    
    function _array2date($dateInput, $timestamp = false)
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
    
    function dateToDatabaseCallback($value)
    {
        return $value;
    }
    
	function prepareLinkedDataObject(&$linkedDataObject, $field) {
		//you may want to include one or both of these
		if ($linkedDataObject->tableName() == 'location') {
			if (isset($this->location_id)) {
				$linkedDataObject->whereAdd('standard=1 OR id='.$this->location_id);
			} else {
				$linkedDataObject->standard = 1;
			}
		}
    }
    
    function insert()
    {
        $r = parent::insert();
        if ($r) {
            UNL_UCBCN::cleanCache();
        }
        return $r;
    }
    
    function update()
    {
        $r = parent::update();
        if ($r) {
            UNL_UCBCN::cleanCache();
        }
        return $r;
    }
    
    function delete()
    {
        $r = parent::delete();
        if ($r) {
            UNL_UCBCN::cleanCache();
        }
        return $r;
    }
}
