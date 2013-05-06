<?php
namespace UNL\UCBCN\Event\Webcast;

use UNL\UCBCN\ActiveRecord\Record;
/**
 * Table Definition for webcastlink
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
class Link extends Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $webcast_id;                      // int(10)  not_null unsigned
    public $url;                             // blob(4294967295)  blob
    public $sequencenumber;                  // int(10)  unsigned
    public $related;                         // string(1)

    public static function getTable()
    {
        return 'webcastlink';
    }

    
    function table()
    {
        return array(
            'id'=>129,
            'webcast_id'=>129,
            'url'=>66,
            'sequencenumber'=>1,
            'related'=>2,
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
        return array('webcast_id' => 'webcast:id');
    }
}
