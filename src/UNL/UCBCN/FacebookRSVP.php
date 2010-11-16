<?php
/**
 * Facebook RSVP class.
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
 */

/**
 * Facebook RSVP Class
 * This class contains methods contains variables needed for RSVP.
 * The RSVP logic is actually done in Javascrip in the template page.
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_FacebookRSVP
{
    public $facebook;
    public $config;
    public $account;
    
    /** Consructor
     * Initializes all variables for the class on class declaration.
     * 
     * @param int $eventdatetimeId = the Eventdaetime id to be associated with the facebook event.
     * @param int $calendarId = calendar id to be associated with the facebook event.
     * 
     * @return void
     **/
    function __construct($eventdatetimeId, $calendarId)
    {
        $this->facebook = UNL_UCBCN::factory('facebook');
        $this->facebook->eventdatetime_id = $eventdatetimeId;
        $this->facebook->calendar_id = $calendar_id; //search by current calendar...
        $this->facebook->find(true);  //Assumes non-recurring events
        $this->config = UNL_UCBCN_FacebookInstance::getConfig();
        $this->account = UNL_UCBCN::factory('facebook_accounts');
        $this->account->calendar_id = $calendarId;
        $this->account->find(true);
    }
    
}