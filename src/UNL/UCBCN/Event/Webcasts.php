<?php
namespace UNL\UCBCN\Event;

use UNL\UCBCN\ActiveRecord\RecordList;

use UNL\UCBCN\ActiveRecord\Record;
use UNL\UCBCN\UnexpectedValueException;

/**
 * Object related to a list of webcasts for a specific event.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN
 * @copyright 2014 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

/**
 * This class holds all the webcasts for the list.
 *
 * @package   UNL_UCBCN
 * @copyright 2014 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class Webcasts extends RecordList
{
    function __construct($options = array())
    {
        if (!isset($options['event_id'])) {
            throw new UnexpectedValueException('You must pass an event_id', 500);
        }

        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        return array(
            'listClass' =>  __CLASS__,
            'itemClass' => __NAMESPACE__ . '\\Webcast',
        );
    }

    public function getSQL()
    {
        return 'SELECT id FROM webcast WHERE webcast.event_id = ' . (int)$this->options['event_id'];
    }
}
