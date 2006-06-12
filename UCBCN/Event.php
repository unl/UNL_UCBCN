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
    
    var $fb_fieldLabels = array(	'eventtype_id'		=> 'Event Type',
    								'othertype'			=> 'Secondary Event Type',
    								'shortdescription'	=> 'Short Description',
    								'startdate'			=> 'Start Date',
    								'starttime'			=> 'Start Time',
    								'webpageurl'		=> 'Event Webpage',
    								'privatecomment'	=> 'Internal Note',
    								'imageurl'			=> 'Add An Image',
    								'imagetitle'		=> 'Image Title');

    var $fb_hiddenFields = array('datecreated','uidcreated','datelastupdated','uidlastupdated');
    
    function preGenerateForm(&$fb)
    {
    	foreach ($this->fb_hiddenFields as $el) {
    		$this->fb_preDefElements[$el] = HTML_QuickForm::createElement('hidden',$el,$el);
    	}
    	$this->fb_preDefElements['imageurl'] = HTML_QuickForm::createElement('file','imageurl',$this->fb_fieldLabels['imageurl']);
    }
    
    function postGenerateForm(&$form, &$formBuilder)
    {
    	$form->insertElementBefore(HTML_QuickForm::createElement('html','<fieldset>'),'eventtype_id');
    	$form->insertElementBefore(HTML_QuickForm::createElement('html','</fieldset>'),'webpageurl');
    }
}
