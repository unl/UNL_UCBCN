<?php
/**
 * Table Definition for user
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
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
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
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
    
    public $fb_hidePrimaryKey = false;
    public $fb_hiddenFields   = array('account_id','datecreated','uidcreated','datelastupdated','uidlastupdated','accountstatus');
    public $fb_fieldLabels    = array('calendar_id'=>'Default Calendar');
    
    public function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    }
    
    public function postGenerateForm(&$form, &$formBuilder)
    {
    	if (isset($this->uid) && !empty($this->uid)) {
    		$el =& $form->getElement('uid');
    		$el->freeze();
    	}
    }
    
	public function prepareLinkedDataObject(&$ldo, $field) {
	    switch($ldo->tableName()) {
	        case 'user_has_permission':
	            if (isset($_SESSION['calendar_id'])) {
	                $ldo->calendar_id = $_SESSION['calendar_id'];
	            }
	            break;
	        case 'calendar':
	            if (isset($_SESSION['calendar_id'])) {
	               $ldo->whereAdd('id = '.$_SESSION['calendar_id']);
	            }
	            break;
	    }
	}
    
    public function update()
    {
    	$this->datelastupdated = date('Y-m-d H:i:s');
		return parent::update();
    }
    
    public function insert()
	{
		$this->datecreated     = date('Y-m-d H:i:s');
		$this->datelastupdated = date('Y-m-d H:i:s');
		return parent::insert();
	}
	
	public function __toString()
	{
	    return $this->uid;
	}
}
