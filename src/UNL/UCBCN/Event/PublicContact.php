<?php
/**
 * Table Definition for publiccontact
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
 * ORM for a record within the database.
 *
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Event_Publiccontact extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null unsigned
    public $name;                            // string(100)
    public $jobtitle;                        // string(100)
    public $organization;                    // string(100)
    public $addressline1;                    // string(255)
    public $addressline2;                    // string(255)
    public $room;                            // string(255)
    public $city;                            // string(100)
    public $state;                           // string(2)
    public $zip;                             // string(10)
    public $emailaddress;                    // string(100)
    public $phone;                           // string(50)
    public $fax;                             // string(50)
    public $webpageurl;                      // blob(4294967295)  blob

    public function getTable()
    {
        return 'publiccontact';
    }

    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'name'=>2,
            'jobtitle'=>2,
            'organization'=>2,
            'addressline1'=>2,
            'addressline2'=>2,
            'room'=>2,
            'city'=>2,
            'state'=>2,
            'zip'=>2,
            'emailaddress'=>2,
            'phone'=>2,
            'fax'=>2,
            'webpageurl'=>66
        );
    }
    
    function links()
    {
        return array('event_id' => 'event:id');
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
}
