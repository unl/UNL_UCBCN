<?php
/**
 * Table Definition for calendar
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Calendar extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calendar';                        // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $account_id;                      // int(10)  not_null multiple_key unsigned
    public $name;                            // string(255)  
    public $shortname;                       // string(100)  
    public $eventreleasepreference;          // string(255)  
    public $calendardaterange;               // int(10)  unsigned
    public $formatcalendardata;              // blob(-1)  blob
    public $uploadedcss;                     // blob(-1)  blob
    public $uploadedxsl;                     // blob(-1)  blob
    public $emaillists;                      // blob(-1)  blob
    public $calendarstatus;                  // string(255)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(255)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(255)  
    public $externalforms;                   // string(255)  
    public $website;                         // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Calendar',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_hiddenFields 	= array(	'account_id',
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
    var $fb_fieldLabels	= array(	'eventreleasepreference'=>'Event Release Preference',
    									'shortname'		=> 'Short Name (this will change your calendar web address)',
    									'emaillists'	=> 'Email Lists (separated by commas)');
    var $fb_enumFields		= array('eventreleasepreference');
    var $fb_enumOptions	= array('eventreleasepreference'=>array('Immediate','Pending'));
    var $fb_linkDisplayFields = array('name','shortname');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
    
    function postGenerateForm(&$form,&$formBuilder) {
		$el =& $form->getElement('shortname');
		$el->freeze();
	}
	
	/**
	 * Adds a user to the calendar. Grants all permissions to the 
	 * user for the current calendar.
	 *
	 * @param UNL_UCBCN_User $user
	 */
	function addUser($user)
	{
	    if (isset($this->id)) {
		    $p = UNL_UCBCN::factory('permission');
	        $p->find();
	        while ($p->fetch()) {
	                if (!$b->userHasPermission($user,$p->name,$this)) {
	                        $b->grantPermission($user->uid,$this->id,$p->id);
	                }
	        }
	        return true;
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
	function removeUser($user)
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
	 * Removes the given event from the calendar_has_event table.
	 *
	 * @param UNL_UCBCN_Event $event
	 */
	function removeEvent($event)
	{
	    if (isset($this->id) && isset($event->id)) {
		    $calendar_has_event = $this->factory('calendar_has_event');
	        $calendar_has_event->calendar_id = $this->id;
	        $calendar_has_event->event_id = $event->id;
	        return $calendar_has_event->delete();
	    } else {
	        return false;
	    }
	}
}
