<?php
/**
 * Table Definition for eventdatetime
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Eventdatetime extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'eventdatetime';                   // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null unsigned
    public $location_id;                     // int(10)  not_null unsigned
    public $starttime;                       // datetime(19)  binary
    public $endtime;                         // datetime(19)  binary
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
    										'additionalpublicinfo'	=> 'Additional Public Info');
    var $fb_dateTimeElementFormat	= 'g:i a M d Y';
    var $fb_hiddenFields			= array('event_id');
    var $fb_excludeFromAutoRules	= array('event_id');
    var $fb_linkNewValue			= true;
    var $fb_addFormHeader			= false;
    var $fb_formHeaderText			= 'Event Location, Date and Time';
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
    
    function preProcessForm(&$values, &$formBuilder)
    {
    	// Capture event_id foreign key if needed.
    	if (isset($GLOBALS['event_id'])) {
    		$values['event_id'] = $GLOBALS['event_id'];
    	}
    }
}
