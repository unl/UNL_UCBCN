<?php
namespace UNL\UCBCN\Calendar;

class Subscriptions extends \UNL\UCBCN\Subscriptions
{
    protected $calendar_id;
    function __construct($options = array())
    {
        if (!isset($options['calendar_id'])) {
            throw new RuntimeException('You must pass a calendar ID', 500);
        }
        parent::__construct($options);
    }

    function getSQL()
    {
        $sql = 'SELECT * FROM calendar_has_subscription WHERE calendar_id = '.(int)$this->options['calendar_id']';
        return $sql;
    }
}