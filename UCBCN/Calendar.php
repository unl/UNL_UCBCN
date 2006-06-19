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
    public $account_id;                      // int(10)  not_null unsigned
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
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
}
