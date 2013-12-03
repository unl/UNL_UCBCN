<?php
namespace UNL\UCBCN;

use UNL\UCBCN\ActiveRecord\RecordList;

use UNL\UCBCN\ActiveRecord\Record;
/**
 * Object related to a list of events.
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
 * This class holds all the events for the list.
 * 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class EventListing extends RecordList
{
    public function getDefaultOptions()
    {
        return array(
            'listClass' =>  __CLASS__,
            'itemClass' => __NAMESPACE__ . '\\Event\\Occurrence',
        );
    }

    /**
     * Populates events with a listing of events for the calendar given.
     *
     * @param array $options Associative array of options
     *         'year'      int                Year of the events
     *         'month'     int                Month
     *         'day'       int                Day
     *         'calendar'  UNL_UCBCN_Calendar Calendar to fetch events for (optional).
     *         'orderby'   string             ORDER BY sql clause.
     * 
     * @return void
     */
    function constructDayEventInstanceList($options, &$oar)
    {
        $this->type = 'day';
        include_once 'Calendar/Day.php';
        $day           = new Calendar_Day($options['year'], $options['month'], $options['day']);
        $eventdatetime = UNL_UCBCN::factory('eventdatetime');
        $recurringdate = UNL_UCBCN::factory('recurringdate');
        $orderby       = 'eventdatetime.starttime ASC';

        if (isset($options['orderby'])) {
            $orderby =     $options['orderby'];
        }

        // determine if there are any recurring dates
        $rstr = array('', '');
        $recurringdate->query('SELECT * FROM recurringdate');
        if ($recurringdate->fetch()) {
            $rstr[0]  = ', recurringdate ';
            $rstr[1]  = 'AND (eventdatetime.recurringtype = \'none\'' .
                            'OR eventdatetime.recurringtype = \'\')' . 
                        'OR (eventdatetime.event_id = recurringdate.event_id ' .
                            'AND recurringdate.recurringdate = \''.date('Y-m-d', $day->getTimestamp()).'\'' .
                            'AND recurringdate.unlinked = FALSE)';
        }

        $calendar = null;

        if (isset($options['calendar'])) {
            $calendar =& $options['calendar'];
            $eventdatetime->query('SELECT DISTINCT eventdatetime.* FROM calendar_has_event,eventdatetime '.$rstr[0].
                            'WHERE calendar_has_event.calendar_id='.$calendar->id.' ' .
                                    'AND (calendar_has_event.status =\'posted\' OR calendar_has_event.status =\'archived\') '.
                                    'AND calendar_has_event.event_id = eventdatetime.event_id ' .
                                    'AND (eventdatetime.starttime LIKE \''.date('Y-m-d ', $day->getTimestamp()).'%\'' .
                                    $rstr[1] . ') ' .
                            'ORDER BY '.$orderby);
        } else {
            //$eventdatetime->whereAdd('starttime LIKE \''.date('Y-m-d', $day->getTimestamp()).'%\'');
            //$eventdatetime->orderBy($orderby);
            //$eventdatetime->find();
            $eventdatetime->query('SELECT DISTINCT eventdatetime.* FROM eventdatetime '.$rstr[0].
                                  'WHERE eventdatetime.starttime LIKE \''.date('Y-m-d', $day->getTimestamp()).
                                  '%\' '.$rstr[1]);
        }

        while ($eventdatetime->fetch()) {
            // Populate the events to display.
            $event =& $this->events[];
            $event = new UNL_UCBCN_EventInstance($eventdatetime, $calendar);
            // If it's a recurring date, fix URL and starttime
            $rec = $eventdatetime->getDatabaseConnection();
            $sql = 'SELECT recurringdate, recurrence_id, ongoing FROM recurringdate '.
                   'WHERE event_id='.$eventdatetime->event_id.' '.
                   'AND recurringdate LIKE \''.date('Y-m-d', $day->getTimestamp()).'\'' .
                   'AND unlinked = FALSE;';
            $res =& $rec->query($sql);
            $recurrences = $res->fetchRow();
            if ($recurrences) {
                $event->fixRecurringEvent($event, $recurrences[0], $recurrences[1], $recurrences[2]);
            }
            // Save ongoing recurring events for constructOngoingEventList
            if ($recurrences[2]) {
                if ($recurrences[1]) {
                    $oar[] = array_pop($this->events);
                } else {
                    array_pop($this->events);
                }
            }
        }
    }
    
    /**
     * Constructs a list of upcoming events for the given calendar.
     *
     * @param array $options Associative array of options, orderby, limit, calendar
     * 
     * @return void
     */
    public function constructUpcomingEventList($options)
    {
        $this->type = 'upcoming';
        if (isset($options['orderby'])) {
            $orderby =  $options['orderby'];
        } else {
            $orderby = 'eventdatetime.starttime ASC';
        }
        if (isset($options['limit'])) {
            $limit = $options['limit'];
        } else {
            $limit = 10;
        }
        
        if (isset($options['calendar'])) {
            $calendar =& $options['calendar'];
            $mdb2     = $calendar->getDatabaseConnection();
            $sql      = 'SELECT eventdatetime.id, eventdatetime.starttime FROM event,calendar_has_event,eventdatetime ' .
                                'WHERE calendar_has_event.calendar_id='.$calendar->id.' ' .
                                                'AND (calendar_has_event.status =\'posted\' OR calendar_has_event.status =\'archived\') '.
                                                'AND calendar_has_event.event_id = eventdatetime.event_id ' .
                                                'AND calendar_has_event.event_id = event.id ' .
                                                'AND eventdatetime.starttime >= \'' . date('Y-m-d') . '\' '.
                                'ORDER BY '.$orderby.' LIMIT '.(int)$limit;
        } else {
            $mdb2     = UNL_UCBCN::getDatabaseConnection();
            $calendar = null;
            $sql      = 'SELECT eventdatetime.id FROM eventdatetime WHERE '.
                        'eventdatetime.starttime >= \'' . date('Y-m-d') . '\' '.
                        'ORDER BY '.$orderby.' LIMIT '.(int)$limit;
        }
        $res = $mdb2->query($sql)->fetchAll();
        $sql = 'SELECT eventdatetime.id, recurringdate.recurringdate, ' .
               'recurringdate.recurrence_id FROM recurringdate, eventdatetime, calendar_has_event ' .
               'WHERE recurringdate > \'' . date('Y-m-d') . '\' ' .
               'AND (calendar_has_event.status =\'posted\' OR calendar_has_event.status =\'archived\') '.
               'AND eventdatetime.event_id = recurringdate.event_id ' .
               'AND calendar_has_event.event_id = recurringdate.event_id ' .
               'AND recurringdate.ongoing = FALSE ' .
               'AND recurringdate.unlinked = FALSE ' .
               'AND calendar_has_event.calendar_id = '.$calendar->id.' ' .
               'ORDER BY recurringdate LIMIT 10;';
        $rec_res = $mdb2->query($sql);
        $recurring_events = $rec_res->fetchAll();
        for ($i = 0; $i < count($recurring_events); $i++) {
            for ($j = 0; $j < count($res); $j++) {
                if (strtotime($recurring_events[$i][1]) < strtotime($res[$j][1])) {
                    $recurring_events[$i][2] = true; // Set recurrence flag
                    $front = ($j == 0) ? array() : array_slice($res, 0, $j);
                    $end = array_slice($res, $j);
                    $res = array_merge($front, array($recurring_events[$i]), $end);
                    break;
                }
            }
            if (strtotime($recurring_events[$i][1]) > strtotime($res[count($res)-1][1])) {
                $recurring_events[$i][2] = true; // Set recurrence flag
                array_push($res, $recurring_events[$i]);
            }
        }
        while (count($res) > $limit) {
            array_pop($res);
        }
        foreach ($res as $row) {
            $event =& $this->events[];
            // Populate the events to display.
            $event = new UNL_UCBCN_EventInstance($row[0], $calendar);

            // Check recurrence flag
            if (isset($row[2])
                && $row[2]) {
                $event->fixRecurringEvent($event, $row[1]);
            }
        }
    }
    
    /**
     * Constructs a list of ongoing events.
     *
     * @param array $options Associative array of options, year, month, day, orderby
     * @param array $oar     Events which are ongoing and recurring
     * 
     * @return void
     */
    public function constructOngoingEventList($options, $oar)
    {
        $this->type = 'ongoing';

        include_once 'Calendar/Day.php';

        $day           = new Calendar_Day($options['year'], $options['month'], $options['day']);
        $eventdatetime = UNL_UCBCN::factory('eventdatetime');

        $orderby = 'eventdatetime.starttime ASC';
        if (isset($options['orderby'])) {
            $orderby = $options['orderby'];
        }

        $calendar = null;
        if (isset($options['calendar'])) {
            $calendar =& $options['calendar'];
            $eventdatetime->query('SELECT DISTINCT eventdatetime.* FROM calendar_has_event,eventdatetime ' .
                            'WHERE calendar_has_event.calendar_id='.$calendar->id.' ' .
                                    'AND (calendar_has_event.status =\'posted\' OR calendar_has_event.status =\'archived\') '.
                                    'AND calendar_has_event.event_id = eventdatetime.event_id ' .
                                    'AND eventdatetime.starttime < \''.date('Y-m-d', $day->getTimestamp()).'\' ' .
                                    'AND eventdatetime.endtime >= \''.date('Y-m-d', $day->getTimestamp()).'\' ' .
                            'ORDER BY '.$orderby);
        } else {
            $eventdatetime->whereAdd('starttime LIKE \''.date('Y-m-d', $day->getTimestamp()).'%\'');
            $eventdatetime->orderBy($orderby);
            $eventdatetime->find();
        }

        while ($eventdatetime->fetch()) {
            // Populate the events to display.
            $this->events[] = new UNL_UCBCN_EventInstance($eventdatetime, $calendar);
        }

        while (count($oar)) {
            $this->events[] = array_shift($oar);
        }
    }
}
