<?php
/**
 * Table Definition for document
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
class UNL_UCBCN_Document extends UNL_UCBCN_Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $event_id;                        // int(10)  not_null unsigned
    public $name;                            // string(100)
    public $url;                             // string(255)

    public function getTable()
    {
        return 'document';
    }

    function table()
    {
        return array(
            'id'=>129,
            'event_id'=>129,
            'name'=>2,
            'url'=>2,
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
