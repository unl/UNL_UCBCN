<?php
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
 * Require DB_DataObject to extend from it, as well as the backend UNL_UCBCN.
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN.php';

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
class UNL_UCBCN_Event extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event';                           // table name
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

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    public $fb_formHeaderText = 'Event Sharing Status';
    public $fb_preDefOrder = array(
                                    'approvedforcirculation',
                                    'consider',
                                    'title',
                                    'subtitle',
                                    'description',
                                    '__reverseLink_event_has_eventtype_event_id',
                                    'othereventtype',
                                    '__reverseLink_eventdatetime_event_id',
                                    'listingcontactname',
                                    'listingcontactphone',
                                    'listingcontactemail');
    
    public $fb_fieldLabels = array( 'othereventtype'         => 'Secondary Event Type',
                                    'shortdescription'       => 'Short Description',
                                    'webpageurl'             => 'Event Webpage',
                                    'privatecomment'         => 'Internal Note',
                                    'imageurl'               => 'Existing Image URL',
                                    'imagedata'              => 'Upload/Attach an Image',
                                    'imagetitle'             => 'Image Title',
                                    'approvedforcirculation' => '',
                                    'otherkeywords'          => 'Other Keywords',
                                    'listingcontactname'     => 'Name',
                                    'listingcontactphone'    => 'Phone',
                                    'listingcontactemail'    => 'Email',
                                    '__reverseLink_eventdatetime_event_id' => '',
                                    '__reverseLink_event_has_eventtype_event_id'=>'',
                                    '__reverseLink_event_has_sponsor_event_id'=>'',
                                    'consider'=>'');

    public $fb_hiddenFields = array('datecreated',
                                    'uidcreated',
                                    'datelastupdated',
                                    'uidlastupdated',
                                    'status',
                                    'othereventtype',
                                    'classification',
                                    'transparency',
                                    'imagemime',
                                    'icalendar');
    
    public $fb_enumFields          = array('approvedforcirculation');
    public $fb_enumOptions         = array('approvedforcirculation'=>array('Private (Your event will only be available to your calendar)<br />',
                                                                           'Public (Your event will be available to any calendar)<br />'));
    
    public $fb_linkNewValue        = array('__reverseLink_eventdatetime_event_idlocation_id_1',
                                            'location_id');

    public $fb_reverseLinks        = array(array('table'=>'eventdatetime'),
                                           array('table'=>'event_has_eventtype'),
                                           array('table'=>'event_has_sponsor'));
    
    public $fb_reverseLinkNewValue = true;
    
    public $fb_linkElementTypes    = array('__reverseLink_eventdatetime_event_id'=>'subForm',
                                           '__reverseLink_event_has_eventtype_event_id'=>'subForm',
                                           '__reverseLink_event_has_sponsor_event_id'=>'subForm',
                                           'approvedforcirculation'=>'radio');
    
    public $fb_textAreaFields      = array('description');
    
    /**
     * Called before the form values are processed and populate the object.
     *
     * @param array                     &$values Associative array of post data.
     * @param DB_DataObject_FormBuilder &$fb     Formbuilder object
     *
     * @return void
     */
    function preProcessForm(&$values, &$fb)
    {
        if (strpos($values['listingcontactemail'], '@') === false
            && !empty($values['listingcontactemail'])
            && isset($GLOBALS['email_domain'])) {
            $values['listingcontactemail'] = $values['listingcontactemail'] . '@' . $GLOBALS['email_domain'];
        }
    }
    
    /**
     * Called before the form is generated.
     *
     * @param DB_DataObject_FormBuilder &$fb The FormBuilder
     *
     * @return void
     */
    public function preGenerateForm(&$fb)
    {
        global $_UNL_UCBCN;
        foreach ($this->fb_hiddenFields as $el) {
            $this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden', $fb->elementNamePrefix.$el.$fb->elementNamePostfix);
        }
        $this->fb_preDefElements['imagedata'] = HTML_QuickForm::createElement('file', 'imagedata', $this->fb_fieldLabels['imagedata']);
        if (isset($_UNL_UCBCN['default_calendar_id']) &&
            isset($_SESSION['calendar_id']) &&
            ($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
            include_once 'UNL/UCBCN/Calendar_has_event.php';
            $this->fb_preDefElements['consider'] = HTML_QuickForm::createElement('checkbox', 'consider', $this->fb_fieldLabels['consider'], 'Please consider event for the main calendar');
            if (isset($this->id)) {
                $che = UNL_UCBCN::factory('calendar_has_event');
                $che->calendar_id = $_UNL_UCBCN['default_calendar_id'];
                $che->event_id = $this->id;
                if ($che->find()) {
                    // This event has already been considered.
                    $this->fb_preDefElements['consider']->setChecked(true);
                    $this->fb_preDefElements['consider']->freeze();
                }
            }
        }
        if (isset($this->uidcreated)) {
            $el = HTML_QuickForm::createElement('text', 'uidcreated', 'Originally Created By', $this->uidcreated);
            $el->freeze();
            $this->fb_preDefElements['uidcreated'] =& $el;
            unset($this->fb_reverseLinks);
            $fb->reverseLinks = array();
        }
    }
    
    /**
     * Called after the form is generated for additional formatting.
     *
     * @param HTML_QuickForm            &$form        The form
     * @param DB_DataObject_Formbuilder &$formBuilder The formbuilder
     *
     * @return void
     */
    public function postGenerateForm(&$form, &$formBuilder)
    {
        $form->insertElementBefore(HTML_QuickForm::createElement('header', 'detailsheader', 'Event Details'), 'title');
        $form->insertElementBefore(HTML_QuickForm::createElement('header', 'contactheader', 'Contact Information'), 'listingcontactname');
        $form->insertElementBefore(HTML_QuickForm::createElement('header', 'eventlocationheader', 'Event Location, Date and Time'), '__reverseLink_eventdatetime_event_id');
        $form->insertElementBefore(HTML_QuickForm::createElement('header', 'optionaldetailsheader', 'Additional Details (Optional)'), 'shortdescription');
        $form->updateElementAttr('approvedforcirculation', 'id="approvedforcirculation"');
        $form->updateElementAttr('uidcreated', 'id="uidcreated"');
        
        foreach ($this->fb_textAreaFields as $name) {
            $el =& $form->getElement($name);
            $el->setRows(4);
            $el->setCols(50);
        }
        
        foreach (array('title','subtitle') as $name) {
            $el =& $form->getElement($name);
            $el->setSize(50);
        }
        
        $el =& $form->getElement('webpageurl');
        $el->setCols(50);
        $el->setRows(2);
        
        $form->addRule('imageurl', 'Image URL must be valid, be sure to include http://', 'callback', array('UNL_UCBCN_Event','checkURL'));
        $form->addRule('webpageurl', 'Web Page URL must be valid, be sure to include http://', 'callback', array('UNL_UCBCN_Event','checkURL'));
        
        $date = date('Y-m-d H:i:s');
        
        $defaults = array('datecreated'     => $date,
                          'datelastupdated' => $date);

        if (isset($_SESSION['_authsession'])) {
            if (!isset($this->uidcreated)) {
                $defaults['uidcreated']=$_SESSION['_authsession']['username'];
            }
            $defaults['uidlastupdated']=$_SESSION['_authsession']['username'];
        }
        
        if (!empty($this->datecreated)) {
            $defaults['datecreated'] = $this->datecreated;
        }
        
        if (isset($this->approvedforcirculation)) {
            $defaults['approvedforcirculation'] = $this->approvedforcirculation;
        } else {
            $defaults['approvedforcirculation'] = 1;
        }
        $el =& $form->getElement('approvedforcirculation');
        unset($el->_elements[0]);
        $form->setDefaults($defaults);
    }
    
    /**
     * Simple function to test for a valid URL
     *
     * Used to check webpageurl and imageurl fields.
     *
     * @param string $val URL to check
     *
     * @return int 0 | 1
     */
    public function checkURL($val)
    {
        return preg_match('/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/i', $val);
    }
    
    /**
     * Called before linked dataobjects are used to restrict the results to a subset
     *
     * @param object &$linkedDataObject The dataobject to be linked.
     * @param string $field             The field which the linked object is used for
     *
     * @return void
     */
    public function prepareLinkedDataObject(&$linkedDataObject, $field)
    {
        if ($linkedDataObject->tableName() == 'eventdatetime' ||
            $linkedDataObject->tableName() == 'event_has_eventtype' ||
            $linkedDataObject->tableName() == 'event_has_sponsor') {
            // Here we are limiting the reverseLink records to only relevant records.
            if (ctype_digit($this->id)) {
                $linkedDataObject->event_id     = $this->id;
            } else {
                $linkedDataObject->id            = 0;
            }
        }
    }
    
    /**
     * Returns an associative array of the fields for this table.
     *
     * @return array
     */
    public function table()
    {
        global $_UNL_UCBCN;
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
        
        if (isset($_UNL_UCBCN['default_calendar_id']) &&
            isset($_SESSION['calendar_id']) &&
            ($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
            return array_merge($table, array('consider' => DB_DATAOBJECT_INT));
        } else {
            return $table;
        }
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
        // Delete child elements that would be orphaned.
        if (ctype_digit($this->id)) {
            foreach (array('calendar_has_event',
                           'event_has_keyword',
                           'eventdatetime',
                           'event_has_eventtype',
                           'event_has_sponsor',
                           'event_isopento_audience',
                           'event_targets_audience') as $table) {
                $do = DB_DataObject::factory($table);
                $do->event_id = $this->id;
                $do->delete();
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
}