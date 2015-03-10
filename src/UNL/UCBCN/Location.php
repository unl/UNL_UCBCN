<?php
/**
 * Details for locations within the database.
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
 * ORM for a Location record within the database.
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Location extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'location';                        // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(100)  multiple_key
    public $streetaddress1;                  // string(255)
    public $streetaddress2;                  // string(255)
    public $room;                            // string(100)
    public $city;                            // string(100)
    public $state;                           // string(2)
    public $zip;                             // string(10)
    public $mapurl;                          // blob(4294967295)  blob
    public $webpageurl;                      // blob(4294967295)  blob
    public $hours;                           // string(255)
    public $directions;                      // blob(4294967295)  blob
    public $additionalpublicinfo;            // string(255)
    public $type;                            // string(100)
    public $phone;                           // string(50)
    public $standard;                        // int(1)
    public $user_id;                         // string(100)

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Location',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    public $fb_linkDisplayFields = array('name');
    public $fb_linkNewValue      = true;
    public $fb_fieldLabels       = array('streetaddress1'       => 'Address',
                                         'streetaddress2'       => '',
                                         'additionalpublicinfo' => 'Additional Public Info',
                                         'mapurl'               => 'Map URL',
                                         'webpageurl'           => 'Web Page',
                                         'standard'             => 'Save this location for future events?');
    public $fb_hiddenFields      = array('user_id', 'display_order');
    public $save; // boolean to track whether to save this location for the user

    function table()
    {
        return array(
            'id'=>129,
            'name'=>2,
            'streetaddress1'=>2,
            'streetaddress2'=>2,
            'room'=>2,
            'city'=>2,
            'state'=>2,
            'zip'=>2,
            'mapurl'=>66,
            'webpageurl'=>66,
            'hours'=>2,
            'directions'=>66,
            'additionalpublicinfo'=>2,
            'type'=>2,
            'phone'=>2,
            'standard'=>17,
            'user_id'=>2,
            'display_order'=>129
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

    public function preGenerateForm(&$fb)
    {
        foreach ($this->fb_hiddenFields as $el) {
            $this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
        }
    }

    /**
     * Inserts a new location in the database.
     *
     * @return bool
     */
    public function insert()
    {
        if ($this->standard) {
            if (isset($_SESSION['_authsession'])) {
                $this->user_id = $_SESSION['_authsession']['username'];
            }
            $this->standard = 0;
        } else {
            $this->user_id = NULL;
            $this->standard = 0;
        }
        $result = parent::insert();
        return $result;
    }
    
}
