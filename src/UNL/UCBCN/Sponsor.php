<?php
/**
 * Table Definition for sponsor
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
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Sponsor extends DB_DataObject
{



    public $__table = 'sponsor';                         // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(255)
    public $standard;                        // int(1)
    public $sponsortype;                     // string(255)
    public $webpageurl;                      // blob(4294967295)  blob







    function table()
    {
        return array(
            'id'=>129,
            'name'=>2,
            'standard'=>17,
            'sponsortype'=>2,
            'webpageurl'=>66,
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
}
