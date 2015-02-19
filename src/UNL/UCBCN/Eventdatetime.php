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
    public $recurringtype;                   // string(255)
    public $recurs_until;                    // datetime
    public $rectypemonth;                    // string(255)
    public $room;                            // string(255)
    public $hours;                           // string(255)
    public $directions;                      // blob(4294967295)  blob
    public $additionalpublicinfo;            // blob(4294967295)  blob

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Eventdatetime',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    public $fb_fieldLabels            = array('location_id'        => 'Location',
                                            'starttime'            => 'Start Time',
                                            'endtime'              => 'End Time',
                                            'recurringtype'        => 'Recurring Interval',
                                            'recurs_until'         => 'Recurs Until',
                                            'additionalpublicinfo' => 'Additional Public Info');
    public $fb_elementTypeMap         = array('datetime'=>'jscalendar');
    public $fb_hiddenFields           = array('event_id', 'hours');
    public $fb_excludeFromAutoRules   = array('event_id');
    public $fb_linkNewValue           = true;
    public $fb_addFormHeader          = false;
    public $fb_formHeaderText         = 'Event Location, Date and Time';
    public $fb_dateToDatabaseCallback = array('UNL_UCBCN_Eventdatetime','dateToDatabaseCallback');
    public $fb_preDefOrder            = array('location_id','room');
    
    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'location_id'=>129,
            'starttime'=>14,
            'endtime'=>14,
            'recurringtype'=>2,
            'recurs_until'=>14,
            'rectypemonth'=>2,
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
    
    public function preGenerateForm(&$fb)
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
        $rtoptions = array('none'=>'None',
                           'daily'=>'Daily',
                           'weekly'=>'Weekly',
                           'monthly'=>'Monthly',
                           'annually'=>'Annually');
        $rcattr = array('onchange'=>'toggleMonth("'.$fb->elementNamePrefix.'",this,"'.$fb->elementNamePostfix.'");');
        $ruattr = array('id'=>$fb->elementNamePrefix.'recurs_until'.$fb->elementNamePostfix,
                        'size'=>10,
                        'onchange'=>'toggleMonth("'.$fb->elementNamePrefix.'",this,"'.$fb->elementNamePostfix.'");');
        $rmdisplay = (isset($this->rectypemonth)) ? "display:block" : "display:none";
        $rmattr = array('id'=>$fb->elementNamePrefix.'rectypemonth'.$fb->elementNamePostfix,
                        'style'=>$rmdisplay);
        $rtmlabel = (isset($this->rectypemonth)) ? 'Type of Monthly Recurrence' : '' ;
        $this->fb_preDefElements['starttime'] = new HTML_QuickForm_group('starttime_group','Start Date & Time',
            array(
                HTML_QuickForm::createElement('text', $fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix, null, array('id'=>$fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix, 'size'=>10)),
                HTML_QuickForm::createElement('jscalendar', 'date1_calendar', null, $options),
                HTML_QuickForm::createElement('date',$fb->elementNamePrefix.'starthour'.$fb->elementNamePostfix,null, $dateoptions)
            ), null, false);
        $options['setup']['inputField'] = $fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix;
        $this->fb_preDefElements['endtime'] = new HTML_QuickForm_group('endtime_group','End Date & Time',
            array(
                HTML_QuickForm::createElement('text', $fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix, null, array('id'=>$fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix, 'size'=>10)),
                HTML_QuickForm::createElement('jscalendar', 'date2_calendar', null, $options),
                HTML_QuickForm::createElement('date',$fb->elementNamePrefix.'endhour'.$fb->elementNamePostfix,null, $dateoptions)
            ), null, false);
        $this->fb_preDefElements['recurringtype'] = new HTML_QuickForm_select($fb->elementNamePrefix.'recurringtype'.$fb->elementNamePostfix, 'Recurring Interval', $rtoptions, $rcattr);
        $this->fb_preDefElements['rectypemonth'] = new HTML_QuickForm_select($fb->elementNamePrefix.'rectypemonth'.$fb->elementNamePostfix, $rtmlabel, null, $rmattr);
        $options['setup']['inputField'] = $fb->elementNamePrefix.'recurs_until'.$fb->elementNamePostfix;
        $this->fb_preDefElements['recurs_until'] = new HTML_QuickForm_group('recurs_until_group','Recurs Until',
            array(
                HTML_QuickForm::createElement('text', $fb->elementNamePrefix.'recurs_until'.$fb->elementNamePostfix, null, $ruattr),
                HTML_QuickForm::createElement('jscalendar', 'date3_calendar', null, $options),
                HTML_QuickForm::createElement('date',$fb->elementNamePrefix.'recurs_untilhour'.$fb->elementNamePostfix,null, $dateoptions)
            ), null, false);
    }
    
    public function postGenerateForm(&$form, &$fb)
    {
        $defaults = array(
            $fb->elementNamePrefix.'eventdatetime'.$fb->elementNamePostfix => '',
            $fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix     => '',
            $fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix       => '',
        );
        $diff = 0;
        if (isset($this->starttime)) {
            if (isset($_REQUEST['rec']) && isset($_REQUEST['recid'])) {
                $rd = UNL_UCBCN::factory('recurringdate');
                $rd->event_id = $this->event_id;
                $rd->recurrence_id = $_REQUEST['recid'];
                $rd->find(true);
                $defaults[$fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix] = $rd->recurringdate;
                $diff = strtotime($rd->recurringdate) - strtotime(substr($this->starttime, 0, 10));
            } else {
                $defaults[$fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix] = substr($this->starttime,0,10);
            }
            if (substr($this->starttime,11) != '00:00:00') {
                $defaults[$fb->elementNamePrefix.'starthour'.$fb->elementNamePostfix] = substr($this->starttime,11);
            }
        }
        if (isset($this->endtime)) {
            if ($diff && isset($_REQUEST['rec'])) {
                $defaults[$fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix] = date('Y-m-d', strtotime(substr($this->endtime, 0, 10)) + $diff);
            } else {
                $defaults[$fb->elementNamePrefix.'endtime'.$fb->elementNamePostfix] = substr($this->endtime,0,10);
            }
            if (substr($this->endtime,11) != '00:00:00') {
                $defaults[$fb->elementNamePrefix.'endhour'.$fb->elementNamePostfix] = substr($this->endtime,11);
            }
        }
        if (isset($this->recurs_until)) {
            $defaults[$fb->elementNamePrefix.'recurs_until'.$fb->elementNamePostfix] = substr($this->recurs_until,0,10);
            if (substr($this->recurs_until,11) != '00:00:00') {
                $defaults[$fb->elementNamePrefix.'recurs_untilhour'.$fb->elementNamePostfix] = substr($this->recurs_until,11);
            }
        }
        if (isset($this->rectypemonth)) {
            $this->rtmAddOptions();
        }
        
        $form->setDefaults($defaults);
        
        $form->addRule('starttime_group','Start time is required.','required');
        $form->registerRule('date', 'callback', 'strtotime');
        
        $form->registerRule('recur', 'callback', 'check_recurrence', $this);
        $form->addRule('recurs_until_group', 'Please specify a date when recurrence should stop.', 'recur');
        
        $form->addGroupRule('starttime_group', array(
            $fb->elementNamePrefix.'starttime'.$fb->elementNamePostfix => array(
                array('Start Date is required', 'required'),
                array('Invalid Date', 'date'),
            )
            ));
    }
    
    public function preProcessForm(&$values, &$fb)
    {
        // Holds the changed starttime and endtime for recurring events
        $datetime = array();
        // Time of recurring date since epoch.
        $rdtime = array();
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
            //Transpose starttime if this is a recurring event
            if (isset($values['rec']) && isset($values['recid'])) {
                //Save changed starttime
                $datetime['starttime'] = $starttime.' '.$this->_array2date($values['starthour']);
                //Get this date
                $rd = $this->factory('recurringdate');
                $rd->event_id = $values['event_id'];
                $rd->recurrence_id = $values['recid'];
                $rd->ongoing = 'FALSE';
                $rd->find(true);
                $rdtime[0] = strtotime($rd->recurringdate);
                $sttime = strtotime($starttime);
                $diff = $sttime - $rdtime[0];
                //Get first date
                $rd = $this->factory('recurringdate');
                $rd->event_id = $values['event_id'];
                $rd->recurrence_id = 0;
                $rd->ongoing = 'FALSE';
                $rd->find(true);
                $rdtime[1] = strtotime($rd->recurringdate);
                //Set starttime
                $starttime = date('Y-m-d', $rdtime[1] + $diff);
            }
            if (isset($values['rec']) && $values['rec'] != 'all') {
                //Save master starttime
                $edt = UNL_UCBCN::factory('eventdatetime');
                $edt->get($values['id']);
                $values['starttime'] = $edt->starttime;
            } else {
                $values['starttime'] = $starttime.' '.$this->_array2date($values['starthour']);
            }
        }
        if (isset($values['endhour'])) {
            if (empty($values['endtime'])) {
                $values['endtime'] = $starttime;
            } else if (isset($values['rec']) && isset($values['recid'])) {
                $edt = UNL_UCBCN::factory('eventdatetime');
                $edt->get($values['id']);
                if ($values['rec'] == 'all') {
                    $endtime = date('Y-m-d', strtotime($values['endtime']));
                    $edtime = strtotime($endtime);
                    $diff = $edtime - $rdtime[0];
                    $values['endtime'] = date('Y-m-d', $rdtime[1] + $diff).' '.$this->_array2date($values['endhour']);
                } else {
                    $datetime['endtime'] = $values['endtime'].' '.$this->_array2date($values['endhour']);
                    $values['endtime'] = $edt->endtime;
                    if (strtotime($datetime['endtime']) < strtotime($datetime['starttime'])) {
                        $datetime['endtime'] = $datetime['starttime'];
                    }
                }
            } else {
                $values['endtime'] = $values['endtime'].' '.$this->_array2date($values['endhour']);
            }
            //endtime cannot be less than starttime 
            if (strtotime($values['endtime']) < strtotime($values['starttime'])) {
                $values['endtime'] = $values['starttime'];
            }
        }
        if ($values['recurringtype'] == 'none') {
            $values['recurs_until'] = '';
        }
        if (!empty($values['recurs_until'])) {
            $recurs_untilhour = $this->_array2date($values['recurs_untilhour']);
            if ($values['rec'] == 'all') {
                $starthour = $this->_array2date($values['starthour']); 
            } else {
                $starthour = substr($datetime['starttime'], 11);
            } 
            if (strtotime($recurs_untilhour) < strtotime($starthour)) {
                $values['recurs_untilhour'] = $values['starthour'];
            }
            $values['recurs_until'] .= ' '.$this->_array2date($values['recurs_untilhour']);
        }
        if ($values['recurringtype'] != 'monthly') {
            $values['rectypemonth'] = '';
        }
        if (isset($values['rec']) && isset($values['recid'])) {
            $rd = $this->factory('recurringdate');
            //$rd->unlinkEvents($values['rec'], $values['recid'], $values['event_id'], $datetime);
            $rd->unlinkEvents($this->__table, $values, $datetime);
        }
    }
    
    public function check_recurrence()
    {
        $rtvalue = $this->fb_preDefElements['recurringtype']->_values[0];
        $ruvalue = $this->fb_preDefElements['recurs_until']->_elements[0]->_attributes['value'];
        
        if ($rtvalue != "none" && empty($ruvalue)) {
            return false;
        }
        
        return true;
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
            if (isset($month) && ($len = strlen($month)) > 0) {
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
                $linkedDataObject->whereAdd('standard=1 OR id='.$this->location_id.' OR user_id='.$_SESSION['_authsession']['username']);
            } else {
                $linkedDataObject->whereAdd('standard=1 OR user_id="'.$_SESSION['_authsession']['username'].'"');
                $linkedDataObject->orderBy('user_id DESC, name ASC');
            }
        }
    }
    
    public function insert()
    {
        $r = parent::insert();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
        return $r;
    }
    
    public function update()
    {
        $r = parent::update();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
      
        return $r;
    }
    
    public function delete()
    {
        //delete the actual event.
        $r = parent::delete();
        if ($r) {
            UNL_UCBCN::cleanCache();
            $this->factory('recurringdate')->updateRecurringEvents();
        }
        return $r;
    }
    
    /**
     * Gets an object for the location of this event date and time.
     *
     * @return UNL_UCBCN_Location
     */
    public function getLocation()
    {
        if (isset($this->location_id)) {
            return $this->getLink('location_id');
        } else {
            return false;
        }
    }
    
    protected function rtmAddOptions()
    {
        $nth             = array(1 => "First",
                                 2 => "Second",
                                 3 => "Third",
                                 4 => "Fourth",
                                 5 => "Last");
        $time            = strtotime($this->recurs_until);
        $date            = date("d", $time);
        $longweekday     = date("l", $time);
        $weekday         = date("D", $time);
        $daysinmonth     = date("t", $time);
        $month           = date("m", $time);
        $week            = 1;
        $weekdaysinmonth = 1; // how many times $weekday occurs in this month
        // make $date look pretty
        if (($tempdate = substr($date, 1)) == "1" || $tempdate == "2" || $tempdate == "3") {
            $date .= substr($nth[$tempdate], -2);
        } else {
            $date .= substr($nth[4], -2);
        }
        if (($tempdate = substr($date, 0, 1)) == "0") {
            $date = substr($date, 1);
        }
        $t = $time;
        // go back as long as we're still in this month
        while (($d = date("m", $t = strtotime("last ".$weekday, $t))) == $month) {
            $week++;
            $weekdaysinmonth++;
        }
        $t = $time;
        // go forward as long as we're still in this month
        while (($d = date("m", $t = strtotime("next ".$weekday, $t))) == $month) {
            $weekdaysinmonth++;
        }
        // actually add the options
        $startweekday = date('l', strtotime($this->starttime));
        if ($startweekday == $longweekday) {
            $text  = $nth[$week]." $longweekday of every month";
            $value = strtolower($nth[$week]);
            $attr  = array("id"=>"nth");
            $this->fb_preDefElements['rectypemonth']->addOption($text, $value, $attr);
            if ($week == 4 && $weekdaysinmonth == 4) {
                $text = "Last $longweekday of every month";
                $value = "last";
                $attr = array("id"=>"last");
                $this->fb_preDefElements['rectypemonth']->addOption($text, $value, $attr);
            }
        }
        $startday = date('d', strtotime($this->starttime));
        $day = date('d', strtotime($this->recurs_until));
        if ($startday == $day) {
            $text  = "$date of every month";
            $value = "date";
            $attr  = array("id"=>"date");
            $this->fb_preDefElements['rectypemonth']->addOption($text, $value, $attr);
        }
        if ($day == $daysinmonth) {
            $text  = "Last day of every month";
            $value = "lastday";
            $attr  = array("id"=>"lastday");
            $this->fb_preDefElements['rectypemonth']->addOption($text, $value, $attr);
        }
    }
}
