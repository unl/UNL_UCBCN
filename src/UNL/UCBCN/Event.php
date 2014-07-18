<?php
namespace UNL\UCBCN;

use UNL\UCBCN\ActiveRecord\Record;
/**
 * Table Definition for event
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
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class Event extends Record
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $title;                           // string(100)  not_null multiple_key
    public $subtitle;                        // string(100)
    public $othereventtype;                  // string(255)
    public $description;                     // blob(4294967295)  blob
    public $shortdescription;                // string(255)
    public $refreshments;                    // string(255)
    public $classification;                  // string(100)
    public $approvedforcirculation;          // int(1)
    public $transparency;                    // string(255)
    public $status;                          // string(100)
    public $privatecomment;                  // blob(4294967295)  blob
    public $otherkeywords;                   // string(255)
    public $imagetitle;                      // string(100)
    public $imageurl;                        // blob(4294967295)  blob
    public $webpageurl;                      // blob(4294967295)  blob
    public $listingcontactuid;               // string(255)
    public $listingcontactname;              // string(100)
    public $listingcontactphone;             // string(255)
    public $listingcontactemail;             // string(255)
    public $icalendar;                       // blob(4294967295)  blob
    public $imagedata;                       // blob(4294967295)  blob binary
    public $imagemime;                       // string(255)
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)

    public static function getTable()
    {
        return 'event';
    }

    /**
     * Returns an associative array of the fields for this table.
     *
     * @return array
     */
    public function table()
    {
        $table = array(
            'id'=>129,
            'title'=>130,
            'subtitle'=>2,
            'othereventtype'=>2,
            'description'=>66,
            'shortdescription'=>2,
            'refreshments'=>2,
            'classification'=>2,
            'approvedforcirculation'=>17,
            'transparency'=>2,
            'status'=>2,
            'privatecomment'=>66,
            'otherkeywords'=>2,
            'imagetitle'=>2,
            'imageurl'=>66,
            'webpageurl'=>66,
            'listingcontactuid'=>2,
            'listingcontactname'=>2,
            'listingcontactphone'=>2,
            'listingcontactemail'=>2,
            'icalendar'=>66,
            'imagedata'=>66,
            'imagemime'=>2,
            'datecreated'=>14,
            'uidcreated'=>2,
            'datelastupdated'=>14,
            'uidlastupdated'=>2
        );

        return $table;

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
        return array('listingcontactuid' => 'users:uid',
                     'uidcreated'        => 'users:uid',
                     'uidlastupdated'    => 'users:uid');
    }
    
    /**
     * This function processes any posted files,
     * sepcifically the images for an event.
     *
     * Called from insert() or update().
     *
     * @return void
     */
    public function processFileAttachments()
    {
        if (isset($_FILES['imagedata'])
            && is_uploaded_file($_FILES['imagedata']['tmp_name'])
            && $_FILES['imagedata']['error']==UPLOAD_ERR_OK) {
            global $_UNL_UCBCN;
            $this->imagemime = $_FILES['imagedata']['type'];
            $this->imagedata = file_get_contents($_FILES['imagedata']['tmp_name']);
        }
    }
    
    /**
     * Inserts a new event in the database.
     *
     * @return bool
     */
    public function insert()
    {
        global $_UNL_UCBCN;
        if (isset($this->consider)) {
            // The user has checked the 'Please consider this event for the main calendar'
            $add_to_default = $this->consider;
            unset($this->consider);
        } else {
            $add_to_default = 0;
        }
        $this->processFileAttachments();
        $this->datecreated = date('Y-m-d H:i:s');
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidcreated=$_SESSION['_authsession']['username'];
            $this->uidlastupdated=$_SESSION['_authsession']['username'];
        }
        $result = parent::insert();
        if ($result) {
            // If insert was successful, set a global variable for any child elements to see the event_id foreign key.
            $GLOBALS['event_id'] = $this->id;
            if ($add_to_default && isset($_UNL_UCBCN['default_calendar_id'])) {
                // Add this as a pending event to the default calendar.
                $this->addToCalendar($_UNL_UCBCN['default_calendar_id'], 'pending', 'checked consider event');
            }
        }
        return $result;
    }
    
    /**
     * Updates the record for this event in the database.
     *
     * @param mixed $do DataObject
     *
     * @return bool
     */
    public function update($do=false)
    {
        global $_UNL_UCBCN;
        $GLOBALS['event_id'] = $this->id;
        if (isset($this->consider)) {
            // The user has checked the 'Please consider this event for the main calendar'
            $add_to_default = $this->consider;
            unset($this->consider);
        } else {
            $add_to_default = 0;
        }
        if (is_object($do) && isset($do->consider)) {
            unset($do->consider);
        }
        $this->datelastupdated = date('Y-m-d H:i:s');
        if (isset($_SESSION['_authsession'])) {
            $this->uidlastupdated=$_SESSION['_authsession']['username'];
        }
        $this->processFileAttachments();
        $res = parent::update();
        if ($res) {
            if ($add_to_default && isset($_UNL_UCBCN['default_calendar_id'])) {
                // Add this as a pending event to the default calendar.
                $che = UNL_UCBCN::factory('calendar_has_event');
                $che->calendar_id = $_UNL_UCBCN['default_calendar_id'];
                $che->event_id = $this->id;
                if ($che->find()==0) {
                    $this->addToCalendar($_UNL_UCBCN['default_calendar_id'], 'pending', 'checked consider event');
                }
            }
            //loop though all eventdateandtime instances for this event.
            $events = UNL_UCBCN_Manager::factory('eventdatetime');
            $events->whereAdd('eventdatetime.event_id = '.$this->id);
            $number = $events->find();
            while ($events->fetch()) {
                $facebook = new \UNL\UCBCN\Facebook\Instance($events->id);
                $facebook->updateEvent();
                
            }
        }
        return $res;
    }
    
    /**
     * This function will add the current event to the default calendar.
     * It assumes that the global default_calendar_id is set.
     *
     * @param int    $calendar_id ID of the calendar to add the event to
     * @param string $status      Status to add as, pending | posted | archived
     * @param string $sourcemsg   Message for the source of this addition.
     *
     * @return int|false
     */
    public function addToCalendar($calendar_id, $status='pending', $sourcemsg = 'unknown')
    {
        $values = array(
                'calendar_id'     => $calendar_id,
                'event_id'        => $this->id,
                'uidcreated'      => $_SESSION['_authsession']['username'],
                'datecreated'     => date('Y-m-d H:i:s'),
                'datelastupdated' => date('Y-m-d H:i:s'),
                'uidlastupdated'  => $_SESSION['_authsession']['username'],
                'status'          => $status,
                'source'          => $sourcemsg);
        return UNL_UCBCN::dbInsert('calendar_has_event', $values);
    }
    
    /**
     * Performs a delete of this event and all child records
     *
     * @return bool
     */
    public function delete()
    {
        //get all facebook events for this id and delete them.
            $events = UNL_UCBCN_Manager::factory('eventdatetime');
            $events->whereAdd('eventdatetime.event_id = '.$this->id);
            $number = $events->find();
            while ($events->fetch()) {
                $facebook = new \UNL\UCBCN\Facebook\Instance($events->id);
                $facebook->deleteEvent();
            }
          
        // Delete child elements that would be orphaned.
        if (ctype_digit($this->id)) {
            foreach (array('calendar_has_event',
                           'event_has_keyword',
                           'eventdatetime',
                           'event_has_eventtype',
                           'event_has_sponsor',
                           'event_isopento_audience',
                           'event_targets_audience') as $table) {
                self::getDB()->query('DELETE FROM '.$table.' WHERE event_id = '.$this->id);
            }
        }
        return parent::delete();
    }
    
    /**
     * Check whether this event belongs to any calendars.
     *
     * @return bool
     */
    public function isOrphaned()
    {
        if (isset($this->id)) {
            $calendar_has_event = UNL_UCBCN::factory('calendar_has_event');
            $calendar_has_event->event_id = $this->id;
            return !$calendar_has_event->find();
        } else {
            return false;
        }
    }
    
    /**
     * Adds other information to the array produced by $event->toArray().
     * If $event already contains these values, this function can be called with
     * just the first parameter. Otherwise the values must be supplied.
     * 
     * @param UNL_UCBCN_Event $event  the event to call toArray() on
     * @param mixed           $ucee   optional whether the current user can edit $event
     * @param mixed           $ucde   optional whether the user can delete $event
     * @param mixed           $che    optional status if calendar has event, false otherwise
     * @param mixed           $rec_id optional recurrence_id
     * 
     * @return array
     */
    public function eventToArray($event, $ucee=null, $ucde=null, $che=null, $rec_id=false)
    {
        if ($ucee === null && $ucde === null && $che === null) {
            // assume these values to be supplied by $event
            $ucee = $event->usercaneditevent;
            $ucde = $event->usercandeleteevent;
            $che  = $event->calendarhasevent;
            if (isset($event->recurrence_id)) {
                $rec_id = $event->recurrence_id;
            }
        }
        $other_event_info = array(
            'usercaneditevent'=>$ucee,
            'usercandeleteevent'=>$ucde,
            'calendarhasevent'=>$che
        );
        if ($rec_id !== false) {
            $other_event_info['recurrence_id'] = $rec_id;
        }
        $event = array_merge($event->toArray(), $other_event_info);
        return $event;
    }
    
    /**
     * Converts an associative array generated by UNL_UCBCN_Event::toArray()
     * back into an object of type UNL_UCBCN_Event. It can also take an object
     * of type UNL_UCBCN_Event or stdClass as its parameter. This ensures that the
     * value returned is an object of UNL_UCBCN_Event and not stdClass.
     * 
     * @param mixed $event associative array, UNL_UCBCN_Event, or stdClass
     * to convert to UNL_UCBCN_Event. 
     * 
     * @return UNL_UCBCN_Event
     */
    public function arrayToEvent($event)
    {
        $e = UNL_UCBCN::factory('event');
        foreach ($event as $key => $value) {
            $e->$key = $value;
        }
        return $e;
    }

    /**
     * Get documents for this event
     * 
     * @return Documents
     */
    public function getDocuments()
    {
        return new Event\Documents(array('event_id' => $this->id));
    }
}