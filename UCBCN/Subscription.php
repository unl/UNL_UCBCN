<?php
/**
 * Table Definition for subscription
 * @package    UNL_UCBCN
 */

/**
 * Require DB_DataObject to extend from it, as well as the Calendar has event class.
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN/Calendar_has_event.php';
/**
 * ORM for a record within the database.
 * @package UNL_UCBCN
 */
class UNL_UCBCN_Subscription extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'subscription';                    // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(100)  
    public $automaticapproval;               // int(1)  not_null
    public $timeperiod;                      // date(10)  binary
    public $expirationdate;                  // date(10)  binary
    public $searchcriteria;                  // blob(-1)  blob
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  
    public $calendar_id;                     // int(10)  not_null multiple_key unsigned

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Subscription',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    
    public $fb_fieldLabels  = array('automaticapproval'=>'Automatically approve events (send to posted)?',
                                    'expirationdate'=>'Expiration Date',
                                    'name'=>'Subscription Title');
    public $fb_enumFields   = array('automaticapproval');
    public $fb_enumOptions  = array('automaticapproval'=>array('No (send to pending)','Yes (send to posted)'));
    public $fb_hiddenFields = array('datecreated',
                                    'uidcreated',
                                    'datelastupdated',
                                    'uidlastupdated',
                                    'calendar_id',
                                    'timeperiod',
                                    'expirationdate');
    public $fb_linkElementTypes = array('automaticapproval'=>'radio');
    
    function preGenerateForm(&$fb)
    {
        global $_UNL_UCBCN;
        foreach ($this->fb_hiddenFields as $el) {
            $this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
        }
        
        $calendars = UNL_UCBCN::factory('calendar');
        $calendars->orderBy('name');
        if ($calendars->find()) {
            while ($calendars->fetch()) {
                $cal_opts[$calendars->id] = $calendars->name;
            }
        }
        $this->fb_preDefElements['searchcriteria'] = HTML_QuickForm::createElement('select','searchcriteria','Events Posted to the Calendar(s)',$cal_opts,'multiple');
    }
    
    /**
     * Translates search criteria into calendars.
     *
     * @param string $text
     */
    function getCalendars($searchcriteria)
    {
        $searchcriteria = explode('=',$searchcriteria);
        $cals = array();
        foreach ($searchcriteria as $c) {
            $calids = array();
            if (preg_match('/[^\d]*([\d]+)[^\d]*/',$c,$calids)) {
                if ($calids[1] != 0) {
                    $cals[] = intval($calids[1]);
                }
            }
        }
        return $cals;
    }
    
    function postGenerateForm(&$form, &$formBuilder)
    {        
        $form->insertElementBefore(HTML_QuickForm::createElement('static','intro','<p>Subscribe to events that match the following criteria:</p>'),'searchcriteria');
    }
    
    function preProcessForm(&$values, &$formBuilder)
    {
        if (isset($_SESSION['calendar_id'])) {
            $values['calendar_id'] = $_SESSION['calendar_id'];
        }
        if (isset($values['searchcriteria'])) {
            $where = '';
            foreach ($values['searchcriteria'] as $calendar_id) {
                $where .= 'calendar_has_event.calendar_id='.intval($calendar_id).' OR ';
            }
            $where .= 'calendar_has_event.calendar_id = 0';
            $values['searchcriteria'] = $where;
        }
    }
    
    function insert()
    {
        global $_UNL_UCBCN;
        $this->datecreated = date('Y-m-d H:i:s');
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidcreated=$_SESSION['_authsession']['username'];
            $this->uidlastupdated=$_SESSION['_authsession']['username'];
        }
        $result = parent::insert();
        if ($result) {
            // If insert was successful, process the subscription immediately if the user chose so.
            if ($this->process()) {
                // Events were added to the current calendar.
            }
        }
        return $result;
    }
    
    function update($do=false)
    {
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidlastupdated=$_SESSION['_authsession']['username'];
        }
        $result = parent::update();
        if ($result) {
            // If insert was successful, process the subscription immediately if the user chose so.
            if ($this->process()) {
                // Events were added to the current calendar.
            }
        }
        return $result;
    }
    
    /**
     * Processes this subscription and adds events not currently 
     * added to the calendar this subscription is for.
     *
     * @param int $event_id Optionally only add the event with the mathcing id.
     * 
     * @return int number of events added to the calendar
     */
    function process($event_id = null)
    {
        $added = 0;
        if (isset($this->id) && isset($this->calendar_id)) {
            $res =& $this->matchingEvents(true);
            if ($res->numRows()) {
                // There are events to insert, postpone any subscription processing until we're done.
                $process_subscriptions = UNL_UCBCN_Calendar_has_event::processSubscriptions();
                UNL_UCBCN_Calendar_has_event::processSubscriptions(false);
                $calendar = $this->factory('calendar');
                $user = $this->factory('user');
                $user->get($this->uidcreated);
                $calendar->get($this->calendar_id);
                $status = $this->getApprovalStatus();
                while ($row = $res->fetchRow()) {
                    $e = $this->factory('event');
                    if ($e->get($row[0])) {
                        $calendar->addEvent($e,$status,$user,'subscription');
                        $added++;
                    }
                }
                // restore process subscriptions to what it was before.
                UNL_UCBCN_Calendar_has_event::processSubscriptions($process_subscriptions);
                self::updateSubscribedCalendars($calendar->id);
            }
        }
        return $added;
    }
    
    /**
     * returns the string equivalent of the automatic approval status, for 
     * inserting into the calendar_has_event database.
     * It will return 'posted' if automatic approval is true.
     * 'pending' otherwise.
     *
     * @return string the Status.
     */
    function getApprovalStatus()
    {
        if ($this->automaticapproval==1) {
            return 'posted';
        } else {
            return 'pending';
        }
    }
    
    /**
     * Finds the events matching this subscription.
     *
     * @return MDB2_Result
     */
    function matchingEvents($exclude_existing = true)
    {
        $mdb2 =& $this->getDatabaseConnection();
        $sql = 'SELECT DISTINCT event.id FROM event,calendar_has_event WHERE calendar_has_event.event_id = event.id ';
        $sql .= ' AND ('.$this->searchcriteria.') AND calendar_has_event.status != \'pending\'';
        if ($exclude_existing) {
            $sql .= ' AND event.id NOT IN (SELECT DISTINCT event.id FROM event, calendar_has_event AS c2 WHERE c2.calendar_id ='.$this->calendar_id.' AND c2.event_id = event.id);';
        }
        $res =& $mdb2->query($sql);
        return $res;
    }
    
    function updateSubscribedCalendars($calendar_id, $event_id = null)
    {
        $updated = 0;
        $subscriptions = UNL_UCBCN::factory('subscription');
        $subscriptions->whereAdd('searchcriteria LIKE \'%calendar_has_event.calendar_id='.$calendar_id.' %\'');
        if ($subscriptions->find()) {
            while ($subscriptions->fetch()) {
                if ($subscriptions->process()) {
                    // Events were added.
                    $updated++;
                }
            }
        }
        return $updated;
    }
    
    /**
     * Checks if a calendar has subscribers.
     * 
     * @param int The id of the calendar to check.
     * 
     * @return int
     */
    function calendarHasSubscribers($calendar_id)
    {
        $subscriptions = UNL_UCBCN::factory('subscription');
        $subscriptions->whereAdd('searchcriteria LIKE \'%calendar_has_event.calendar_id='.$calendar_id.' %\'');
        return $subscriptions->find();
    }
}
