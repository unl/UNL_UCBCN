<?php
/**
 * Table Definition for event_has_eventtype
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Event_has_eventtype extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event_has_eventtype';             // table name
    public $event_id;                        // int(10)  not_null unsigned
    public $eventtype_id;                    // int(10)  not_null unsigned

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event_has_eventtype',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_hiddenFields			= array('event_id');
    var $fb_excludeFromAutoRules	= array('event_id');
    var $fb_fieldLabels			= array('eventtype_id'=>'Event Type');
    var $fb_formHeaderText			= 'Event Type';
    
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
