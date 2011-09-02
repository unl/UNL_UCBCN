<?php 
/**
 * Facebook integration class (db).
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 *
 * TODO: Reoccuring dates.
 * TODO: Create JSON encoded Location info for Facebook.
 * TODO: Only create events if asked to by the user in the edit/create event page.
 */

require_once 'UNL/UCBCN.php';

/**
 * Facebook integration class (db).
 * This class contains the table structor and methods for the php pear
 * DBobject class.
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Facebook_accounts extends UNL_UCBCN_Record
{

    public $id;
    public $facebook_account;
    public $access_token;
    public $page_name;
    public $calendar_id;
    public $create_events;
    public $show_like_buttons;

    public function getTable()
    {
        return 'facebook_accounts';
    }
    
    /** keys for the db table.
     * 
     *  @return The array containing a list of keyes used in the DB table.
     **/
    function keys()
    {
        return array('id','calendar_id'
        );
    }
    
    function sequenceKey()
    {
        return array('id',true);
    }
    
    /** links for the db table.
     * 
     *  @return An array containing a list of linked fields in the db.
     **/
    function links()
    {
        return array();
    }
    
    /** Table Stucture for the DB..
     * 
     *  @return An array containing the DB structure.
     **/
    function table()
    {
        return array(
            'id'=>129,
            'facebook_account'=>17,
            'access_token'=>66,
            'page_name'=>130,
            'calendar_id'=>17,
            'create_events'=>17,
            'show_like_buttons'=>17,
        );
    }
    
    public function createEvents(){
        //check account.
        if ($this->create_events != true) {
            return false;
        }
        if (!isset($this->facebook_account)) {
            return false;
        }
        if (!isset($this->access_token)) {
            return false;
        }
        //Check facebook App settings
        if (!UNL_UCBCN_FacebookInstance::getConfig()) {
            return false;
        }
        return true;
    }
    
}