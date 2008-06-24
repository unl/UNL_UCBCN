<?php
/**
 * Table Definition for performer
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

/**
 * Require DB_DataObject to extend from it.
 */
require_once 'DB/DataObject.php';
/**
 * ORM for a record within the database.
 * 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Performer extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'performer';                       // table name
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

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Performer',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
