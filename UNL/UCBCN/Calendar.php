<?php
/**
 * Details related to a calendar within the UNL Event Publisher system.
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
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Calendar extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar';                        // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $account_id;                      // int(10)  not_null multiple_key unsigned
    public $name;                            // string(255)
    public $shortname;                       // string(100)  multiple_key
    public $website;                         // string(255)
    public $eventreleasepreference;          // string(255)
    public $calendardaterange;               // int(10)  unsigned
    public $formatcalendardata;              // blob(4294967295)  blob
    public $uploadedcss;                     // blob(4294967295)  blob
    public $uploadedxsl;                     // blob(4294967295)  blob
    public $emaillists;                      // blob(4294967295)  blob
    public $calendarstatus;                  // string(255)
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(255)
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(255)
    public $externalforms;                   // string(255)
    public $recommendationswithinaccount;    // int(1)

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Calendar',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    public $fb_hiddenFields      = array(   'account_id',
	                                        'uploadedcss',
	                                        'uploadedxsl',
	                                        'calendarstatus',
	                                        'formatcalendardata',
	                                        'calendardaterange',
	                                        'datecreated',
	                                        'uidcreated',
	                                        'datelastupdated',
	                                        'uidlastupdated',
	                                        'externalforms');
    public  $fb_fieldLabels       = array('eventreleasepreference' => 'Event Release Preference',
	                                      'shortname'  => 'Short Name (this will change your calendar web address)',
	                                      'emaillists' => 'Email Lists (separated by commas)',
                                          'recommendationswithinaccount' => 'Allow event recommendations within this account');
    public  $fb_enumFields        = array('eventreleasepreference');
    public  $fb_enumOptions       = array('eventreleasepreference'=>array('Immediate','Pending'));
    public  $fb_linkDisplayFields = array('name','shortname');
    
    function table()
    {
        return array(
            'id'=>129,
            'account_id'=>129,
            'name'=>2,
            'shortname'=>2,
            'website'=>2,
            'eventreleasepreference'=>2,
            'calendardaterange'=>1,
            'formatcalendardata'=>66,
            'uploadedcss'=>66,
            'uploadedxsl'=>66,
            'emaillists'=>66,
            'calendarstatus'=>2,
            'datecreated'=>14,
            'uidcreated'=>2,
            'datelastupdated'=>14,
            'uidlastupdated'=>2,
            'externalforms'=>2,
            'recommendationswithinaccount'=>17,
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
        return array('account_id'     => 'account:id',
                     'uidcreated'     => 'users:uid',
                     'uidlastupdated' => 'users:uid');
    }
    
    public function preGenerateForm(&$fb)
    {
        foreach ($this->fb_hiddenFields as $el) {
            $this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
        }
    }
    
    public function postGenerateForm(&$form,&$formBuilder) {
        $el =& $form->getElement('shortname');
        $el->freeze();
    }
    
    /**
     * Adds a user to the calendar. Grants all permissions to the
     * user for the current calendar.
     *
     * @param UNL_UCBCN_User $user
     */
    public function addUser(UNL_UCBCN_User $user)
    {
        if (isset($this->id)) {
            $p = UNL_UCBCN::factory('permission');
            $p->find();
            while ($p->fetch()) {
                    if (!UNL_UCBCN::userHasPermission($user,$p->name,$this)) {
                            UNL_UCBCN::grantPermission($user->uid,$this->id,$p->id);
                    }
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Gets a calendar by shortname.
     *
     * @param string $shortname The shortname of the calendar you wish to get.
     *
     * @return UNL_UCBCN_Calendar
     */
    static function getByShortname($shortname)
    {
        $c = new self();
        $c->shortname = $shortname;
        if ($c->find()==1) {
            $c->fetch();
            return $c;
        } else {
            return false;
        }
    }
    
    /**
     * Removes a user from the current calendar.
     * Basically removes all permissions for the user on the current calendar.
     *
     * @param UNL_UCBCN_User $user
     */
    public function removeUser(UNL_UCBCN_User $user)
    {
        if (isset($this->id)&&isset($user->uid)) {
            $sql = 'DELETE FROM user_has_permission WHERE user_uid = \''.$user->uid.'\' AND calendar_id ='.$this->id;
            $mdb2 = $this->getDatabaseConnection();
            return $mdb2->exec($sql);
        } else {
            return false;
        }
    }
    
    /**
     * Adds the event to the current calendar.
     *
     * @param UNL_UCBCN_Event $event
     * @param string          $status posted | pending | archived
     * @param UNL_UCBCN_User  $user   the user adding this event
     * @param string          $source create event form, subscription.
     *
     * @return int > 0 on success.
     */
    public function addEvent(UNL_UCBCN_Event $event,$status, UNL_UCBCN_User $user,$source=null)
    {
        $values = array(
                        'calendar_id'     => $this->id,
                        'event_id'        => $event->id,
                        'uidcreated'      => $user->uid,
                        'datecreated'     => date('Y-m-d H:i:s'),
                        'datelastupdated' => date('Y-m-d H:i:s'),
                        'uidlastupdated'  => $user->uid,
                        'status'          => $status);
        if (isset($source)) {
            $values['source'] = $source;
        }
        $che =& UNL_UCBCN::factory('calendar_has_event');
        foreach ($values as $mv=>$value) {
            $che->$mv = $value;
        }
        return $che->insert();
    }
    
    /**
     * Removes the given event from the calendar_has_event table.
     *
     * @param UNL_UCBCN_Event $event The event to remove from this calendar.
     *
     * @return bool
     */
    public function removeEvent(UNL_UCBCN_Event $event)
    {
        if (isset($this->id) && isset($event->id)) {
            $calendar_has_event              = UNL_UCBCN::factory('calendar_has_event');
            $calendar_has_event->calendar_id = $this->id;
            $calendar_has_event->event_id    = $event->id;
            return $calendar_has_event->delete();
        } else {
            return false;
        }
    }
    
    /**
     * Finds the subscriptions this calendar has, and processes them.
     *
     * @return void
     */
    public function processSubscriptions()
    {
        $subscriptions = UNL_UCBCN::factory('subscription');
        $subscriptions->calendar_id = $this->id;
        if ($subscriptions->find()) {
            while ($subscriptions->fetch()) {
                $subscriptions->process();
            }
        }
    }
}
