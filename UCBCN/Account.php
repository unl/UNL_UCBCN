<?php
/**
 * Table Definition for account
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Account extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'account';                         // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(100)  
    public $streetaddress1;                  // string(255)  
    public $streetaddress2;                  // string(255)  
    public $city;                            // string(100)  
    public $state;                           // string(2)  
    public $zip;                             // string(10)  
    public $phone;                           // string(50)  
    public $fax;                             // string(50)  
    public $email;                           // string(100)  
    public $accountstatus;                   // string(100)  
    public $datecreated;                     // datetime(19)  binary
    public $datelastupdated;                 // datetime(19)  binary
    public $sponsor_id;                      // int(11)  not_null
    public $website;                         // string(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Account',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_fieldLabels = array(
    						'name'		=> 'Account Name',
    						'streetaddress1' => 'Address',
    						'streetaddress2' => '',
    						'sponsor_id'	=> 'Sponsor'
    						);
    
    var $fb_hiddenFields = array(
								'datecreated',
								'datelastupdated',
								'accountstatus');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$el,$el);
    	}
    }
}
