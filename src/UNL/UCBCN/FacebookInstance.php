<?php 
/**
 * Facebook integration class (instance).
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
 * TODO: Create JSON encoded Location info for Facebook.
 */

//Require the PHP facebook SDK
require_once dirname(dirname(dirname((dirname(__file__))))).'/facebook/src/facebook.php';

/**
 * Facebook integration class.
 * This class contains methods for creating and updating facebook events.
 * It can also display "like" buttons for events.
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_FacebookInstance
{
    public $appID;
    public $secret;
    public $access_token;
    public $profileID;
    public $showLikeButtons;
    public $facebookInterface;
    public $startTime;
    public $endTime;
    public $eventdatetime;
    public $event;
    public $facebook;
    public $account;
    public $location;
    
    
    /** Consructor
     * Initializes all variables for the class on class declaration.
     * 
     * @param int $id = the Eventdaetime id to be associated with the facebook event.
     * 
     * @return void
     **/
    function __construct($id)
    {
        $config                 = $this->getConfig();
        $this->appID            = $config["appID"];
        $this->secret           = $config["secret"];
        
        $this->facebookInterface            = $this->initFacebook($this->appID, $this->secret);
        $this->facebook                     = UNL_UCBCN::factory('facebook');
        $this->facebook->eventdatetime_id   = $id;
        $this->eventdatetime                = UNL_UCBCN::factory('eventdatetime');
        if ($this->eventdatetime->get($id)) {
            $number_of_rows = $this->facebook->find();
            while ($this->facebook->fetch()) {
                if (isset($this->facebook->eventdatetime_id)) {
                }
            }
            //get the event class.
            $this->event = $this->eventdatetime->getLink('event_id');
        } else {
            throw new Exception("Could not find that event instance. $id");
        }
        //convert the start and end times to unix format (required by facebook)
        $this->startTime = $this->prepareFacebookEventTime(strtotime($this->eventdatetime->starttime));
        $this->endTime = $this->prepareFacebookEventTime(strtotime($this->eventdatetime->endtime));
    }
    
     /** getConfig
     * loads the config file and returns an array of the vars.  Loads
     * App vars like appID and secret.
     * 
     * @return array() containing the config.
     **/
    public function getConfig()
    {
        //Load vars from config file.
        $config_file = dirname(dirname(dirname(dirname(dirname(__file__))))).'/config.inc.php';
        if (file_exists($config_file)) {
            include $config_file;
        }

        if (!isset($fb_appId) || $fb_appId==0) {
            return false;
        }

        if (!isset($fb_secret) || $fb_secret=="") {
            return false;
        }
        return array("appID"           => $fb_appId, 
                     "secret"          => $fb_secret);
    }
    
    /** initFacebook
     * Initializes a facebook interface object.
     * 
     * @param int $appID the facebook appID.
     * @param int $secret the facebook secret.
     * 
     * @return Facebook object (facebookInterface)
     **/
    public function initFacebook($appID,$secret)
    {
        //Set up the facebook api.
        $facebookInterface = new Facebook(array(
            'appId'  => $appID,
            'secret' => $secret,
            'cookie' => true,
        ));
        return $facebookInterface;
    }
    
    /** like
     * displays a facebook "like" box.
     * 
     * @param string $url   the request uri of the event.
     * @param int    $calID The calendar ID.
     * 
     * @return string The HTML for the facebook "like" box.
     **/
    function like($url, $calID)
    {
        $this->loadAccount($calID);
        if ($this->account->show_like_buttons) {
            return "<iframe src='http://www.facebook.com/plugins/like.php?href=".urlencode(UNL_UCBCN::getBaseURL().$url)."&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:450px; height:80px;' allowTransparency='true'></iframe>";
        }
    }
    
    /** prepareFacebookEventTime
     * Computes the proper time to be given to facebook.
     * 
     * @param int $time unix formated time to be converted.
     * 
     * @return int unix formated facebook time.
     **/
    function prepareFacebookEventTime($time)
    {
        $d = new Date($time);
        
        //Get the timezone offset in seconds.
        $original = $d->getTZOffset()/1000;
        
        //Convert to Los_angeles timezone where the facebook servers are located.
        $timezone = new Date_TimeZone('America/Los_Angeles');
        $d->convertTZ($timezone);
        
        //Get the timezone offset in seconds.
        $new = $d->getTZOffset()/1000;
        
        //Calculate the difference in seconds between the two timezones.
        $newOffset = $original - $new;
 
        return $d->getTime()+$newOffset;
    }
    
    /** updateEvent
     *  Determins if a facebook event needs to be created or edited.
     *  It then creates the event or edits the event based on wether the
     *  event is recurring or not.  Facebook does not currently support recurring
     *  events, so we will not post recurring events to facebook.
     *  
     * @return n/a.
     **/
    function updateEvent()
    {
        //Create an event for all calendars with this event.
        $check           = UNL_UCBCN::factory('calendar_has_event');
        $check->event_id = $this->event->id;
        $rows            = $check->find();
        //Loop though the calanders for this event.
        while ($check->fetch()) {
            $this->loadAccount($check->calendar_id);
            //Are we susposed to create events?
            if ($this->account->createEvents() && $check->status != "pending") {
                //Find the facebook event.
                $facebook                   = UNL_UCBCN::factory('facebook');
                $facebook->eventdatetime_id = $this->facebook->eventdatetime_id;
                $facebook->calendar_id      = $check->calendar_id;
                $rows                       = $facebook->find(true);
                $this->facebook             = $facebook;
                if ($this->eventdatetime->recurringtype == "none") {
                    //Update the event or create a new one.
                    if (isset($this->facebook->facebook_id)) {
                        if ($this->event->approvedforcirculation && isset($this->facebook->facebook_id)) {
                            $this->updateFacebookEvent();
                        } else {
                            //Delete the event because it is no longer approved.
                            $this->deleteEvent();
                        }
                    } else {
                        //Facebook event was not found, we need to create a new one.
                        $this->createFacebookEvent();
                    }
                }
            } else {
                if (isset($this->facebook->facebook_id)) {
                    //facebook event exists, but the event is currently pending.  So... delete it.
                    $this->deleteEvent();
                }
            }
        }//calander loop
    }//function
    
    /** setLocation
     *  Compiles the location infomration for the event and updates the facebook
     *  instance with the information.  
     * 
     *  @return void
     **/
    function setLocation()
    {
        $this->location = $this->eventdatetime->getLocation()->streetaddress1 . " " 
        . $this->eventdatetime->getLocation()->streetaddress2 . " "
        . $this->eventdatetime->getLocation()->city . " "
        . $this->eventdatetime->getLocation()->state . " "
        . $this->eventdatetime->getLocation()->zip . " ";
    }
    
    /** loadAccount
     * loads the proper facebook Account based on calendar ID.
     * 
     * @param int $calender_id = the calendar ID;
     * 
     * @return void
     **/
    function loadAccount($calender_id)
    {
        $this->account              = UNL_UCBCN::factory('facebook_accounts');
        $this->account->calendar_id = $calender_id;
        $this->account->find(true);
    }
    
    /** deleteEvent
     * Deletes the facebook Event.
     * 
     * @param int $recurringdate_id = the id for a recurring event.  default: null.
     * 
     * @return n/a.
     **/
    function deleteEvent($recurringdate_id=null)
    {
        $check           = UNL_UCBCN::factory('calendar_has_event');
        $check->event_id = $this->event->id;
        $rows            = $check->find();
        //Loop though calendars with this event.
        while ($check->fetch()) {
            //Set account.
            $this->loadAccount($check->calendar_id);
            //Get the facebook event
            $facebook                    = UNL_UCBCN::factory('facebook');
            $facebook->eventdatetime_id  = $this->facebook->eventdatetime_id;
            $facebook->calendar_id       = $check->calendar_id;
            $rows                        = $facebook->find(true);
            $this->facebook              = $facebook;
            //check to see if it is a recurring event.
            if ($this->eventdatetime->recurringtype == "none") {
                if (isset($this->facebook->facebook_id)) {
                    $access = $this->getAccess();
                    try{
                        $result = $this->facebookInterface->api(
                            '/'.$this->facebook->facebook_id.'?method=delete&access_token='.$access['access_token'],
                            array('access_token' => $access['access_token']),
                            'POST'
                        );
                    } catch (FacebookApiException $e) {
                        error_log($e);
                    }
                    $this->facebook->delete();
                }
            }
        }//cal loop.
    }
    
    /** deleteEvent
     * Determins the proper access method for the event. Either account or page access.
     * 
     * @return array(access_token=>the access token, id=>the id, name=> the page name).
     **/
    function getAccess()
    {
        //Get page info from facebook.
        $result = $this->facebookInterface->api(
            '/'.$this->account->facebook_account.'/accounts?access_token='.$this->account->access_token,
                        array('access_token' => $this->account->access_token)
        );
        $pageInfo = false; //return false if nothing was found.
        //Check results for the page that you are looking for.
        for ($i=0; $i< count($result['data']); $i++) {
            if (isset($result['data'][$i]['name']) && ($result['data'][$i]['name'] == $this->account->page_name)) {
                $pageInfo['name']         = $result['data'][$i]['name'];
                $pageInfo['id']           = $result['data'][$i]['id'];
                $pageInfo['access_token'] = $result['data'][$i]['access_token'];
            }
        }
        //If the event has already been set up, but by account, use the account access.
        if (isset($this->facebook->facebook_id) && !isset($this->facebook->page_name)) {
            $pageInfo = false;
        }
        if ($pageInfo) {
            //We are going to edit using the page access info.
            $access['access_token'] = $pageInfo['access_token'];
            $access['id']           = $pageInfo['id'];
            $access['page_name']    = $pageInfo['name'];
        } else {
            //We are going to edit using the account access info.
            $access['access_token'] = $this->account->access_token;
            $access['id']           = $this->account->facebook_account;
        }
        return $access;
    }

    /** updaeFacebookEvent
     * Uses the facebook api to update a facebook event.
     * 
     * @return n/a.
     **/
    function updateFacebookEvent()
    {
        $access = $this->getAccess();
        $this->setLocation();
        try{
            $result = $this->facebookInterface->api(
                '/'.$this->facebook->facebook_id,
                'post',
                array('access_token' => $access['access_token'], 
                'description'        => $this->event->description . $this->getEventDescription(), 
                'location'           => $this->location,
                'name'               => $this->event->title,
                'start_time'         => $this->startTime,
                'end_time'           => $this->endTime)
            );
        } catch (FacebookApiException $e) {
            error_log($e);
        }
        $this->facebook->update();
    }
    
    /** getEventDescription
     * appends the description of the event appended with the URL.
     * 
     * @return string (url)
     **/
    function getEventDescription()
    {
        //Get the path.
        $folder = explode('/', $_SERVER["REQUEST_URI"]);
        $path = "";
        //Start from the begining of the request uri (ignoring the last two 'folders' (/manager/index.php....)
        for($i=0; $i<(count($folder)-2); $i++){
            $path .= $folder[$i] . "/";
        }
        $e = new UNL_UCBCN_EventInstance($this->eventdatetime->id);
        $url = "http://".$_SERVER["SERVER_NAME"].$path.html_entity_decode($e->url);
        return $this->event->description . "\n\nLearn more at " . $url;
    }
    
    /** getURL
     * returns the URL of the current page.
     * 
     * @return string (url)
     **/
    function getURL()
    {
        return "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    /** createFacebookEvent
     * Uses the facebook api to create a facebook event.
     * 
     * @return void
     **/
    function createFacebookEvent()
    {
        if (!isset($this->facebook->facebook_id)) { //Check to see if an event has already been made.
            $this->setLocation();
            $access = $this->getAccess();
            try{
                $result = $this->facebookInterface->api(
                    '/'.$access['id'].'/events',  //WUUUUUUT THE PROFILE ID APPARENTY DOES NOTHING
                    'post',
                    array('access_token' => $access['access_token'], 
                          'description'  => $this->event->description . $this->getEventDescription(), 
                          'location'     => $this->location,
                          'name'         => $this->event->title,
                          'start_time'   => $this->startTime,
                          'end_time'     => $this->endTime)
                );
                if(isset($access['page_name'])){
                    $this->facebook->page_name = $access['page_name'];
                    echo "pgggge" . $access['page_name'];
                }
                $this->facebook->facebook_id = (int)$result['id'];
                $this->facebook->insert();
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
    }
}//Class

