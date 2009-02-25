<?php
/**
 * Table Definition for account
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
 * Extend DB_DataObject
 */
require_once 'DB/DataObject.php';

/**
 * UNL_UCBCN_Account object stores information for an account record within
 * the database.
 * 
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
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
    
    public $fb_fieldLabels = array(
                            'name'           => 'Account Name',
                            'streetaddress1' => 'Address',
                            'streetaddress2' => '',
                            'sponsor_id'     => 'Sponsor'
                            );
    
    public $fb_hiddenFields = array(
                                'datecreated',
                                'datelastupdated',
                                'accountstatus');

    public function preGenerateForm(&$fb)
    {
        foreach ($this->fb_hiddenFields as $el) {
            $this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$el,$el);
        }
    }
    
    /**
     * Adds a calendar under this account.
     *
     * @param string $name Name for the calendar
     * @param string $shortname shortname for the calendar.
     * @param UNL_UCBCN_User $user
     * @param bool Grant user access to the calendar?
     * 
     * @return bool
     */
    public function addCalendar($name,$shortname,UNL_UCBCN_User $user,$grant = true)
    {
        
        $calendar = UNL_UCBCN::factory('calendar');
        $calendar->shortname = $shortname;
        if ($calendar->find()) {
            // calendar name already exists
            return false;
        }
        $calendar->shortname = $shortname;
        $calendar->name = $name;
        $calendar->account_id = $this->id;
        $calendar->uidcreated = $user->uid;
        $calendar->uidlastupdated = $user->uid;
        $calendar->datecreated = $calendar->datelastupdated = date('Y-m-d H:i:s');
        
        $res = $calendar->insert();
        if ($res && $grant) {
            $calendar->addUser($user);
        }
        return $res;
    }
}
