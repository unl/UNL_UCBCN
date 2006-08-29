<?php
/**
 * Table Definition for user
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_User extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user';                            // table name
    public $uid;                             // string(100)  not_null primary_key
    public $account_id;                      // int(10)  not_null unsigned
    public $calendar_id;                     // int(10)  unsigned
    public $accountstatus;                   // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_User',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_hidePrimaryKey = false;
    var $fb_hiddenFields = array('account_id','datecreated','uidcreated','datelastupdated','uidlastupdated','accountstatus');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
    
    function postGenerateForm(&$form, &$formBuilder)
    {
    	if (isset($this->uid) && !empty($this->uid)) {
    		$el =& $form->getElement('uid');
    		$el->freeze();
    	}
    }
    
    function update()
    {
    	$this->datelastupdated = date('Y-m-d H:i:s');
		return parent::update();
    }
    
    function insert()
	{
		$this->datecreated = date('Y-m-d H:i:s');
		$this->datelastupdated = date('Y-m-d H:i:s');
		return parent::insert();
	}
}
