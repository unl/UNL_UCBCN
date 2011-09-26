<?php
/**
 * Table Definition for admissioninfo
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
class UNL_UCBCN_Admissioninfo extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null multiple_key unsigned
    public $type;                            // string(255)
    public $obligation;                      // string(100)
    public $contactname;                     // string(100)
    public $contactphone;                    // string(50)
    public $contactemail;                    // string(255)
    public $contacturl;                      // blob(4294967295)  blob
    public $status;                          // string(255)
    public $additionalinfo;                  // blob(4294967295)  blob
    public $deadline;                        // datetime(19)  binary
    public $opendate;                        // datetime(19)  binary

    public function getTable()
    {
        return 'admissioninfo';
    }

    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'type'=>2,
            'obligation'=>2,
            'contactname'=>2,
            'contactphone'=>2,
            'contactemail'=>2,
            'contacturl'=>66,
            'status'=>2,
            'additionalinfo'=>66,
            'deadline'=>14,
            'opendate'=>14,
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
    
    function links()
    {
        return array('event_id' => 'event:id');
    }
}
