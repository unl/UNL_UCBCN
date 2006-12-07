<?php
/**
 * Table Definition for event
 * @package    UNL_UCBCN
 */

/**
 * Require DB_DataObject to extend from it, as well as the backend UNL_UCBCN.
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN.php';
/**
 * ORM for a record within the database.
 * @package UNL_UCBCN
 */
class UNL_UCBCN_Event extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'event';                           // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $title;                           // string(100)  not_null
    public $subtitle;                        // string(100)  
    public $othereventtype;                  // string(255)  
    public $description;                     // blob(-1)  blob
    public $shortdescription;                // string(255)  
    public $refreshments;                    // string(255)  
    public $classification;                  // string(100)  
    public $approvedforcirculation;          // int(1)  
    public $transparency;                    // string(255)  
    public $status;                          // string(100)  
    public $privatecomment;                  // blob(-1)  blob
    public $otherkeywords;                   // string(255)  
    public $imagetitle;                      // string(100)  
    public $imageurl;                        // blob(-1)  blob
    public $webpageurl;                      // blob(-1)  blob
    public $listingcontactuid;               // string(255)  
    public $listingcontactname;              // string(100)  
    public $listingcontactphone;             // string(255)  
    public $listingcontactemail;             // string(255)  
    public $icalendar;                       // blob(-1)  blob
    public $imagedata;                       // blob(-1)  blob binary
    public $imagemime;                       // string(255)  
    public $datecreated;                     // datetime(19)  binary
    public $uidcreated;                      // string(100)  
    public $datelastupdated;                 // datetime(19)  binary
    public $uidlastupdated;                  // string(100)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    var $fb_formHeaderText	= 'Event Sharing Status';
    var $fb_preDefOrder = array(
    								'approvedforcirculation',
    								'consider',
									'title',
    								'subtitle',
    								'description',
    								'__reverseLink_event_has_eventtype_event_id',
    								'othereventtype',
    								'__reverseLink_eventdatetime_event_id');
    
    var $fb_fieldLabels = array(	'othereventtype'		=> 'Secondary Event Type',
    								'shortdescription'		=> 'Short Description',
    								'webpageurl'			=> 'Event Webpage',
    								'privatecomment'		=> 'Internal Note',
    								'imageurl'				=> 'Existing Image URL',
    								'imagedata'             => 'Upload/Attach an Image',
    								'imagetitle'			=> 'Image Title',
    								'approvedforcirculation'=>'',
    								'otherkeywords'			=> 'Other Keywords',
    								'listingcontactname'	=> 'Listing Contact Name',
    								'listingcontactphone'	=> 'Listing Contact Phone',
    								'listingcontactemail'	=> 'Listing Contact Email',
    								'__reverseLink_eventdatetime_event_id' => '',
    								'__reverseLink_event_has_eventtype_event_id'=>'',
    								'consider'=>'');

    var $fb_hiddenFields = array(	'datecreated',
									'uidcreated',
									'datelastupdated',
									'uidlastupdated',
									'status',
									'classification',
									'transparency',
									'imagemime',
									'icalendar');
    
    var $fb_enumFields				= array('approvedforcirculation');
    var $fb_enumOptions			= array('approvedforcirculation'=>array(	'Private (Your event will only be available to your calendar)<br />',
																				'Public (Your event will be available to any university calendar)<br />'));
    
    var $fb_linkNewValue			= array(
											'__reverseLink_eventdatetime_event_idlocation_id_1',
											'location_id');

	var $fb_reverseLinks			= array(array(	'table'=>'eventdatetime'),
											array(	'table'=>'event_has_eventtype'));
	var $fb_reverseLinkNewValue	= true;
	var $fb_linkElementTypes		= array('__reverseLink_eventdatetime_event_id'=>'subForm',
	                                        '__reverseLink_event_has_eventtype_event_id'=>'subForm',
											'approvedforcirculation'=>'radio');
	
	var $fb_textAreaFields        = array('description');
	
    function preGenerateForm(&$fb)
    {
		global $_UNL_UCBCN;
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    	$this->fb_preDefElements['imagedata'] = HTML_QuickForm::createElement('file','imagedata',$this->fb_fieldLabels['imagedata']);
		if (isset($_UNL_UCBCN['default_calendar_id']) &&
			isset($_SESSION['calendar_id']) &&
			($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
			require_once 'UNL/UCBCN/Calendar_has_event.php';
			$this->fb_preDefElements['consider'] = HTML_QuickForm::createElement('checkbox','consider',$this->fb_fieldLabels['consider'],' Please Consider Event for Main UNL Calendar');
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
		    $el = HTML_QuickForm::createElement('text','uidcreated','Originally Created By',$this->uidcreated);
		    $el->freeze();
		    $this->fb_preDefElements['uidcreated'] =& $el;
		}
    }
    
    function postGenerateForm(&$form, &$formBuilder)
    {
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','detailsheader','Event Details'),'title');
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','eventtypeheader','Event Type'),'__reverseLink_event_has_eventtype_event_id');
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','eventlocationheader','Event Location, Date and Time'),'__reverseLink_eventdatetime_event_id');
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','optionaldetailsheader','Additional Details (Optional)'),'shortdescription');
    	$form->updateElementAttr('approvedforcirculation','id="approvedforcirculation"');
    	$form->updateElementAttr('uidcreated','id="uidcreated"');
    	
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
     	
		//$form->addRule(array('webpageurl','imageurl'),'URL must be valid, be sure to include http://','regex','/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/i','client');
		
    	$defaults = array();
    	if (isset($_SESSION['_authsession'])) {
	    	if (!isset($this->uidcreated)) {
	    	    $defaults['uidcreated']=$_SESSION['_authsession']['username'];
	    	}
	    	$defaults['uidlastupdated']=$_SESSION['_authsession']['username'];
    	}
    	
    	if (isset($this->datecreated)) {
    	    $defaults['datecreated'] = $this->datecreated;
    	} else {
    	    $defaults['datecreated'] = date('Y-m-d H:i:s');
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
    
    function prepareLinkedDataObject(&$linkedDataObject, $field) {
		if ($linkedDataObject->tableName() == 'eventdatetime' || $linkedDataObject->tableName() == 'event_has_eventtype') {
			// Here we are limiting the reverseLink records to only relevant records.
			if (ctype_digit($this->id)) {
				$linkedDataObject->event_id 	= $this->id;
			} else {
				$linkedDataObject->id			= 0;
			}
		}
	}
	
	function table()
	{
		global $_UNL_UCBCN;
		$table = parent::table();
		$table['datecreated'] = DB_DATAOBJECT_TXT;
		if (isset($_UNL_UCBCN['default_calendar_id']) &&
			isset($_SESSION['calendar_id']) &&
			($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
			return array_merge($table, array('consider' => DB_DATAOBJECT_INT));
		} else {
			return $table;
		}
	}
	
	/**
	 * This function processes any posted files,
	 * sepcifically the images for an event.
	 * 
	 * Called from insert() or update().
	 *
	 */
	function processFileAttachments()
	{
	    if (isset($_FILES['imagedata']) 
	        && is_uploaded_file($_FILES['imagedata']['tmp_name'])
	        && $_FILES['imagedata']['error']==UPLOAD_ERR_OK) {
            global $_UNL_UCBCN;
	        $this->imagemime = $_FILES['imagedata']['type'];
	        $this->imagedata = file_get_contents($_FILES['imagedata']['tmp_name']);
	    }
	}
	
	function insert()
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
				$this->addToCalendar($_UNL_UCBCN['default_calendar_id'],'pending','checked consider event');
			}
		}
		return $result;
	}
	
	function update($do=false)
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
					$this->addToCalendar($_UNL_UCBCN['default_calendar_id'],'pending','checked consider event');
			    }
			}
    	}
    	return $res;
	}
	
	/**
	 * This function will add the current event to the default calendar.
	 * It assumes that the global default_calendar_id is set.
	 * 
	 * @param int ID of the calendar to add the event to
	 * @param string Status to add as, pending | posted | archived
	 * @param string Message for the source of this addition.
	 */
	function addToCalendar($calendar_id, $status='pending', $sourcemsg = 'unknown')
	{
	    $values = array(
				'calendar_id'	=> $calendar_id,
				'event_id'		=> $this->id,
				'uidcreated'	=> $_SESSION['_authsession']['username'],
				'datecreated'	=> date('Y-m-d H:i:s'),
				'datelastupdated'	=> date('Y-m-d H:i:s'),
				'uidlastupdated'	=> $_SESSION['_authsession']['username'],
				'status'		=> $status,
				'source'		=> $sourcemsg);
		return UNL_UCBCN::dbInsert('calendar_has_event',$values);
	}
	
	function delete() {
		// Delete child elements that would be orphaned.
		if (ctype_digit($this->id)) {
			foreach (array('event_has_keywords','eventdatetime','event_has_eventtype') as $table) {
				$do = DB_DataObject::factory($table);
				$do->event_id = $this->id;
				$do->delete();
			}
		}
		return parent::delete();
	}
	
	/**
	 * Check whether this event belongs to any calendars.
	 */
	function isOrphaned()
	{
	    if (isset($this->id)) {
	        $calendar_has_event = $this->factory('calendar_has_event');
	        $calendar_has_event->event_id = $this->id;
	        return !$calendar_has_event->find();
	    } else {
	        return false;
	    }
	}
}