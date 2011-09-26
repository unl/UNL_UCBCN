<?php
/**
 * Table Definition for performer
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
class UNL_UCBCN_Event_Performer extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $performer_id;                    // int(10)  not_null unsigned
    public $role_id;                         // int(10)  not_null unsigned
    public $event_id;                        // int(10)  not_null unsigned
    public $personalname;                    // string(100)
    public $name;                            // string(255)
    public $jobtitle;                        // string(100)
    public $organizationname;                // string(100)
    public $personalwebpageurl;              // blob(4294967295)  blob
    public $organizationwebpageurl;          // blob(4294967295)  blob
    public $type;                            // string(255)

    public function getTable()
    {
        return 'performer';
    }

    function links()
    {
        return array('event_id' => 'event:id');
    }
    
    function sequenceKey()
    {
        return array('id',true);
    }
}
