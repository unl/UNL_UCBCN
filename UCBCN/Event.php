<?php
/**
 * Table Definition for event
 */
require_once 'DB/DataObject.php';

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
    
    var $fb_preDefOrder = array(
    								'title',
    								'subtitle',
    								'__reverseLink_event_has_eventtype_event_id',
    								'othereventtype',
    								'__reverseLink_eventdatetime_event_id');
    
    var $fb_fieldLabels = array(	'othereventtype'		=> 'Secondary Event Type',
    								'shortdescription'		=> 'Short Description',
    								'webpageurl'			=> 'Event Webpage',
    								'privatecomment'		=> 'Internal Note',
    								'imageurl'				=> 'Add An Image',
    								'imagetitle'			=> 'Image Title',
    								'approvedforcirculation'=>'Approved for Circulation',
    								'otherkeywords'			=> 'Other Keywords',
    								'listingcontactname'	=> 'Listing Contact Name',
    								'listingcontactphone'	=> 'Listing Contact Phone',
    								'listingcontactemail'	=> 'Listing Contact Email',
    								'__reverseLink_eventdatetime_event_id' => '',
    								'__reverseLink_event_has_eventtype_event_id'=>'');

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
    
    var $fb_linkNewValue			= array(
											'__reverseLink_eventdatetime_event_idlocation_id_1',
											'location_id');

	var $fb_reverseLinks			= array(array(	'table'=>'eventdatetime'),
											array(	'table'=>'event_has_eventtype'));
	var $fb_reverseLinkNewValue	= true;
	var $fb_linkElementTypes		= array('__reverseLink_eventdatetime_event_id'=>'subForm');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$fb->elementNamePrefix.$el.$fb->elementNamePostfix);
    	}
    	$this->fb_preDefElements['imageurl'] = HTML_QuickForm::createElement('file','imageurl',$this->fb_fieldLabels['imageurl']);
    }
    
    function postGenerateForm(&$form, &$formBuilder)
    {
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','eventtypeheader','Event Type'),'__reverseLink_event_has_eventtype_event_id');
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','eventlocationheader','Event Location, Date and Time'),'__reverseLink_eventdatetime_event_id');
    	$form->insertElementBefore(HTML_QuickForm::createElement('header','optionaldetailsheader','Additional Details (Optional)'),'description');
    	$form->updateElementAttr('approvedforcirculation','id="approvedforcirculation"');
    	if (isset($_SESSION['_authsession'])) {
	    	$form->setDefaults(array(	'uidcreated'=>$_SESSION['_authsession']['username'],
	    								'uidlastupdated'=>$_SESSION['_authsession']['username']));
    	}
    }
    
    function prepareLinkedDataObject(&$linkedDataObject, $field) {
		if ($linkedDataObject->tableName() == 'eventdatetime') {
			// Here we are limiting the reverseLink records to only relevant records.
			if (ctype_digit($this->id)) {
				$linkedDataObject->event_id 	= $this->id;
			} else {
				$linkedDataObject->id			= 0;
			}
		}
	}
	
	function insert()
	{
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
		}
		return $result;
	}
	
	function update()
	{
		$this->datelastupdated = date('Y-m-d H:i:s');
		if (isset($_SESSION['_authsession'])) {
	    	$this->uidlastupdated=$_SESSION['_authsession']['username'];
    	}
		return parent::update();
	}
	
	function delete() {
		// Delete child elements that would be orphaned.
		if (ctype_digit($this->id)) {
			foreach (array('event_has_keywords') as $table) {
				$do = DB_DataObject::factory($table);
				$do->event_id = $this->id;
				$do->delete();
			}
		}
		return parent::delete();
	}
}
