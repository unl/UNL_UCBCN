<?php
namespace UNL\UCBCN\Event\Webcast;

use UNL\UCBCN\ActiveRecord\RecordList;

use UNL\UCBCN\ActiveRecord\Record;
use UNL\UCBCN\UnexpectedValueException;

/**
 * Object related to a list of links for a specific webcast.
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
 * This class holds all the links for a webcast.
 *
 * @package   UNL_UCBCN
 * @copyright 2014 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class Links extends RecordList
{
    function __construct($options = array())
    {
        if (!isset($options['webcast_id'])) {
            throw new UnexpectedValueException('You must pass an webcast_id', 500);
        }

        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        return array(
            'listClass' =>  __CLASS__,
            'itemClass' => __NAMESPACE__ . '\\Link',
        );
    }

    public function getSQL()
    {
        return 'SELECT id FROM webcastlink WHERE webcastlink.webcast_id = ' . (int)$this->options['webcast_id'];
    }
}
