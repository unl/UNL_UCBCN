<?php
/**
 * Table Definition for relatedevent
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
class UNL_UCBCN_Relatedevent extends UNL_UCBCN_Record
{

    public $event_id;                        // int(10)  not_null unsigned
    public $related_event_id;                // int(10)  not_null unsigned
    public $relationtype;                    // string(100)  not_null

    public function getTable()
    {
        return 'relatedevent';
    }

    
    function table()
    {
        return array(
            'event_id'=>129,
            'related_event_id'=>129,
            'relationtype'=>2
        );
    }
    
    function links()
    {
        return array('event_id'         => 'event:id',
                     'related_event_id' => 'event:id');
    }
    
    function sequenceKey()
    {
        return array(false, false);
    }
}
