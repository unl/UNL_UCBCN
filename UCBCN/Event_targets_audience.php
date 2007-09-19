<?php
/**
 * Table Definition for event_targets_audience
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
class UNL_UCBCN_Event_targets_audience extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event_targets_audience';          // table name
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $audience_id;                     // int(10)  not_null multiple_key unsigned
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event_targets_audience',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    public $fb_hiddenFields			    = array('event_id');
    public $fb_excludeFromAutoRules	    = array('event_id');
    public $fb_fieldLabels        		= array('audience_id'=>'Audience');
    public $fb_addFormHeader			= false;
    public $fb_formHeaderText			= 'Target Audience';
    
    public function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
    
    public function preProcessForm(&$values, &$formBuilder)
    {
    	// Capture event_id foreign key if needed.
    	if (isset($GLOBALS['event_id'])) {
    		$values['event_id'] = $GLOBALS['event_id'];
    	}
    }
}
