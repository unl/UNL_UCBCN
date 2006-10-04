<?php
/**
 * Table Definition for event
 */
require_once 'DB/DataObject.php';
require_once 'UNL/UCBCN.php';

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
    								'imageurl'				=> 'Add An Image',
    								'imagetitle'			=> 'Image Title',
    								'approvedforcirculation'=>'',
    								'otherkeywords'			=> 'Other Keywords',
    								'listingcontactname'	=> 'Listing Contact Name',
    								'listingcontactphone'	=> 'Listing Contact Phone',
    								'listingcontactemail'	=> 'Listing Contact Email',
    								'__reverseLink_eventdatetime_event_id' => '',
    								'__reverseLink_event_has_eventtype_event_id'=>'',
    								'consider'=>'Please Consider Event for Main UNL Calendar');

    var $fb_hiddenFields = array(	'datecreated',
									'uidcreated',
									'datelastupdated',
									'uidlastupdated',
									'status',
									'classification',
									'transparency',
									'imagedata',
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
    	$this->fb_preDefElements['imageurl'] = HTML_QuickForm::createElement('file','imageurl',$this->fb_fieldLabels['imageurl']);
		if (isset($_UNL_UCBCN['default_calendar_id']) &&
			isset($_SESSION['calendar_id']) &&
			($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
			$this->fb_preDefElements['consider'] = HTML_QuickForm::createElement('checkbox','consider',$this->fb_fieldLabels['consider']);
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
    	
    	$defaults = array();
    	$defaults['approvedforcirculation'] = true;
    	if (isset($_SESSION['_authsession'])) {
	    	$defaults['uidcreated']=$_SESSION['_authsession']['username'];
	    	$defaults['uidlastupdated']=$_SESSION['_authsession']['username'];
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
		if (isset($_UNL_UCBCN['default_calendar_id']) &&
			isset($_SESSION['calendar_id']) &&
			($_SESSION['calendar_id'] != $_UNL_UCBCN['default_calendar_id'])) {
			return array_merge(parent::table(), array('consider' => DB_DATAOBJECT_INT));
		} else {
			return parent::table();
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
				$values = array(
						'calendar_id'	=> $_UNL_UCBCN['default_calendar_id'],
						'event_id'		=> $this->id,
						'uidcreated'	=> $_SESSION['_authsession']['username'],
						'datecreated'	=> date('Y-m-d H:i:s'),
						'datelastupdated'	=> date('Y-m-d H:i:s'),
						'uidlastupdated'	=> $_SESSION['_authsession']['username'],
						'status'		=> 'pending',
						'source'		=> 'checked consider event');
				UNL_UCBCN::dbInsert('calendar_has_event',$values);
			}
		}
		return $result;
	}
	
	function update($do=false)
	{
	    $GLOBALS['event_id'] = $this->id;
		if (isset($this->consider)) {
            unset($this->consider);
        }
		if (is_object($do) && isset($do->consider)) {
            unset($do->consider);
        }
		$this->datelastupdated = date('Y-m-d H:i:s');
		if (isset($_SESSION['_authsession'])) {
	    	$this->uidlastupdated=$_SESSION['_authsession']['username'];
    	}
		return parent::update();
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
