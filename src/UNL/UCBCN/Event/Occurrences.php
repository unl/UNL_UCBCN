<?php
namespace UNL\UCBCN\Event;

use UNL\UCBCN\ActiveRecord\RecordList;

class Occurrences extends RecordList
{
    function __construct($options = array())
    {
        if (!isset($options['event_id'])) {
            throw new RuntimeException('You must pass an event_id', 500);
        }
        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        return array(
            'listClass' =>  __CLASS__,
            'itemClass' => __NAMESPACE__ . '\\Occurrence',
        );
    }
}