<?php
/**
 * Table Definition for session
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
class UNL_UCBCN_Session extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'session';                         // table name
    public $user_uid;                        // string(255)  not_null primary_key
    public $lastaction;                      // datetime(19)  not_null binary
    public $data;                            // blob(4294967295)  blob




    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function table()
    {
        return array(
            'user_uid'=>130,
            'lastaction'=>142,
            'data'=>66,
        );
    }

    function keys()
    {
        return array(
            'user_uid',
        );
    }
    
    function sequenceKey()
    {
        return array(false, false);
    }
    
    function links()
    {
        return array('user_uid' => 'user:uid');
    }
    
}
