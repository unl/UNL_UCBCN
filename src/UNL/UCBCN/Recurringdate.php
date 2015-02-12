<?php
/**
 * Table Definition for recurringdate
 */
require_once 'DB/DataObject.php';

class UNL_UCBCN_Recurringdate extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'recurringdate';                  // table name
    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $recurringdate;                   // date(10)  not_null binary
    public $event_id;                        // int(10)  not_null unsigned
    public $recurrence_id;                   // int(10)  not_null unsigned
    public $ongoing;                         // int(1)  
    public $unlinked;                        // int(1)

    const ONE_DAY = 86400;
    const ONE_WEEK = 604800;

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('UNL_UCBCN_Recurringdate',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function table()
    {
        return array(
            'id'=>129,
            'recurringdate'=>135,
            'event_id'=>129,
            'recurrence_id'=>129,
            'ongoing'=>16,
            'unlinked'=>16,
        );
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
    
    /**
     * Unlinks an event.
     * 
     * @param int $event_id      event_id of instance
     * @param int $recurrence_id recurrence_id of instance
     * 
     * @return void
     */
    public function removeInstance($event_id, $recurrence_id)
    {
        $db =& $this->getDatabaseConnection();
        // unlink this instance
        $sql = "UPDATE recurringdate SET unlinked=1
                WHERE event_id=$event_id
                AND recurrence_id=$recurrence_id";
        $db->query($sql);
        // if all instances are unlinked, remove from table
        $sql = "SELECT * FROM recurringdate
                WHERE event_id = {$event_id}
                AND unlinked=FALSE";
        $res =& $db->query($sql);
        $row = $res->fetchRow();
        if (!$row) {
            $event = UNL_UCBCN::factory('event');
            $event->get($event_id);
            $event->delete();
        } else {
            UNL_UCBCN::cleanCache();
        }
    }
    
    /**
     * Determines the days of this month with recurring events.
     * 
     * @param Calendar_Month_Weekdays $month    Month to find events in.
     * @param UNL_UCBCN_Calendar      $calendar Optional calendar to find events for
     *
     * @return an array with values representing the days with recurring events.
     */
    public function getRecurringDates($month, $calendar = null)
    {
        $mdays = $month->fetchAll();
        $first = date('Y-m-d H:i:s', array_shift($mdays)->getTimestamp());
        $last  = date('Y-m-d H:i:s', array_pop($mdays)->getTimestamp());
        
        // Get recurring events for $month
        $rd = $this->factory('recurringdate');
        $rd->selectAdd();
        $rd->selectAdd('recurringdate');
        $rd->whereAdd("recurringdate >= '$first'");
        $rd->whereAdd("recurringdate <= '$last'");
        $rd->whereAdd("unlinked = FALSE");

        if ($calendar) {
            $che = $this->factory('calendar_has_event');
            $rd->joinAdd($che, 'INNER');
            $rd->whereAdd('calendar_has_event.calendar_id = '.(int) $calendar->id);
            $rd->whereAdd('calendar_has_event.status != "pending"');
        }

        $rd->find();
        
        $res = array();
        
        while ($rd->fetch()) {
            $res[] = date('m-d', strtotime($rd->recurringdate));
        }
        
        return $res;
    }

    /**
     * Deletes the recurring events for a given event.
     * @param event_id     $event_id    The event id to delete recurrences for.
     * 
     * @return void
     */
    public function deleteRecurringEvents($event_id) {
        $db =& $this->getDatabaseConnection();

        // clear out the current recurring dates for this event, that are still linked
        $sql = "DELETE FROM recurringdate WHERE event_id = {$event_id} AND unlinked = FALSE;";
        $db->query($sql);
    }
    
    /**
     * Inserts the recurring events for a given event.
     * @param event_id     $event_id    The event id to insert recurrences for.
     * 
     * @return void
     */
    public function insertRecurringEvents($event_id)
    {
        $event_id = (int)($event_id);
        $new_rows = array();

        $db =& $this->getDatabaseConnection();
        // get all eventdatetimes for this event that are recurring (recurringtype != none)
        $event_date_times =& $db->query("SELECT starttime, endtime, recurringtype, rectypemonth, recurs_until FROM eventdatetime WHERE recurringtype != 'none' AND event_id = {$event_id};");

        while (($row = $event_date_times->fetchRow())) {
            $start_date = strtotime($row[0]); // Y-m-d H:i:s string -> int
            $end_date = strtotime($row[1]); // Y-m-d H:i:s string -> int
            $recurring_type = $row[2];
            $rec_type_month = $row[3];
            $recurs_until = strtotime($row[4]); // Y-m-d H:i:s string -> int
            $k = 0; // this counts the recurrence_id, i.e. which recurrence of the event it is

            $this_start = $start_date;
            $this_end = $end_date;
            $length = $end_date - $start_date;

            // while the current start time is before recurs until
            while ($this_start <= $recurs_until) {
                // insert initial day recurrence for this eventdatetime and recurrence, not ongoing, not unlinked
                $new_rows[] = array(date('Y-m-d', $this_start), $event_id, $k, 0, 0);

                // generate more day recurrences for each day of the event, if it is ongoing (i.e., the end date is the next day or later)
                $next_day = strtotime('midnight tomorrow', $this_start);
                while ($next_day <= $this_end) {
                    // add an entry to recurring dates for this eid, the temp date, is ongoing, not unlinked
                    $new_rows[] = array(date('Y-m-d', $next_day), $event_id, $k, 1, 0);
                    // increment day
                    $next_day = $next_day + self::ONE_DAY;
                }

                // increment k, which is the recurrence counter (not for the day recurrence, but for the normal recurrence)
                $k++;

                // now we move this_start up, based on the recurrence type, and the while loop sees if that is
                // after the recurs_until
                if ($recurring_type == 'daily') {
                    $this_start += self::ONE_DAY;
                } else if ($recurring_type == 'weekly') {
                    $this_start += self::ONE_WEEK;
                } else if ($recurring_type == 'monthly') {
                    // figure out some preliminary things
                    $hour_on_start_date = date('H', $start_date);
                    $minute_on_start_date = date('i', $start_date);
                    $second_on_start_date = date('s', $start_date);

                    $next_month_num = (int)(date('n', $this_start)) + 1;
                    $next_month_year = (int)(date('Y', $this_start));
                    if ($next_month_num > 12) {
                        $next_month_num -= 12;
                        $next_month_year += 1;
                    }
                    $days_in_next_month = cal_days_in_month(CAL_GREGORIAN, $next_month_num, $next_month_year);

                    // now work how to get next month's day
                    if ($rec_type_month == 'date') {
                        $day_for_next_month = min($days_in_next_month, (int)(date('j', $start_date)));
                        $this_start = mktime($hour_on_start_date, $minute_on_start_date, $second_on_start_date, $next_month_num, $day_for_next_month, $next_month_year);
                    } else if ($rec_type_month == 'lastday') {
                        $this_start = mktime($hour_on_start_date, $minute_on_start_date, $second_on_start_date, $next_month_num, $days_in_next_month, $next_month_year);
                    } else { // first, second, third, fourth, or last
                        $weekday = date('l', $start_date);
                        $month_name = date('F', strtotime("2015-{$next_month_num}-01"));
                        $this_start = strtotime("{$rec_type_month} {$weekday} of {$month_name} {$next_month_year}");
                        $this_start = strtotime(date('Y-m-d', $this_start) . ' ' . $hour_on_start_date . ':' . $minute_on_start_date . ':' . $second_on_start_date);
                    }
                } else if ($recurring_type == 'annually' || $recurring_type == 'yearly') { 
                    $this_start = strtotime('+1 year', $this_start);
                } else {
                    // dont want an infinite loop
                    break;
                }
                $this_end = $this_start + $length;
            }

        }

        if (!empty($new_rows)) {
            $sql = "INSERT INTO recurringdate (recurringdate, event_id, recurrence_id, ongoing, unlinked) VALUES ";
            $value_strings = array();
            foreach ($new_rows as $row) {
                $value_strings[] = "('{$row[0]}'," . implode(",", array_slice($row, 1)) . ")";
            }
            $sql .= implode(",", $value_strings) . ";";

            $ret =& $db->query($sql);
            if (PEAR::isError($ret)) {
                error_log($ret->getMessage());
            }
        }

        return;
    }
    
    /**
     * Get eventdatetime information for an instance of a recurring event.
     * 
     * @param int    $recid recurringdate.recurrence_id of instance
     * @param object $edt   Eventdatetime of event
     * 
     * @return object of UNL_UCBCN_Eventdatetime
     */
    public function getInstanceDateTime($recid, $edt)
    {
        $rec = UNL_UCBCN::factory('recurringdate');
        $rec->event_id = $edt->event_id;
        $rec->recurrence_id = $recid;
        $rec->ongoing = 'FALSE';
        $rec->find(true);
        $recurringdate = $rec->recurringdate;
        $rec = UNL_UCBCN::factory('recurringdate');
        $rec->event_id = $edt->event_id;
        $rec->recurrence_id = 0;
        $rec->ongoing = 'FALSE';
        $rec->find(true);
        $originaldate = $rec->recurringdate;
        $edt->starttime = $recurringdate.' '.substr($edt->starttime, 11);
        $diff = strtotime($recurringdate) - strtotime($originaldate);
        $edate = date('Y-m-d', strtotime(substr($edt->endtime, 0, 10)) + $diff);
        $edt->endtime = $edate.' '.substr($edt->endtime, 11);
        return $edt; 
    }
    
    /**
     * Takes an EventListing, finds the related recurring events,
     * and returns them merged in sorted order with the EventListing. 
     * 
     * @param UNL_UCBCN_EventListing $listing Event listing
     * 
     * @return UNL_UCBCN_EventListin $listing Sorted event listing
     */
    public function getInstanceEvents($listing)
    {
        $status = $listing->status;
        $events = array();
        $recurring_events = array();
        // find related events, separate into recurring and non-recurring
        foreach ($listing->events as $key => $e) {
            $is_array = is_array($e);
            $e = (object) $e;
            $recurringdate = UNL_UCBCN_Manager::factory('recurringdate');
            $recurringdate->event_id = $e->id;
            $recurringdate->whereAdd("ongoing = FALSE");
            if ($status == 'posted') {
                $recurringdate->whereAdd('recurringdate >= "'.date('Y-m-d').'"');
            } else if ($status == 'archived') {
                $recurringdate->whereAdd('recurringdate < "'.date('Y-m-d').'"');
            }
            $recurringdate->orderBy("recurringdate DESC");
            $recurring = $recurringdate->find();
            while ($recurringdate->fetch()) {
                if (!$recurringdate->unlinked) {
                    if (is_null($status) && isset($e->calendarhasevent)) {
                        $edt = UNL_UCBCN::factory('eventdatetime');
                        $edt->get('event_id', $e->id);
                        $edt = UNL_UCBCN_Recurringdate::getInstanceDateTime(
                                    $recurringdate->recurrence_id, $edt);
                        if (strtotime($edt->starttime) < strtotime(date('Y-m-d'))) {
                            $e->calendarhasevent = 'archived';
                        } else {
                            $e->calendarhasevent = 'posted';
                        }
                    }
                    $event = UNL_UCBCN_Event::arrayToEvent($e);
                    $event->recurrence_id = $recurringdate->recurrence_id;
                    // if this function was called from showSearchResults
                    if ($is_array) {
                        $event = UNL_UCBCN_Event::eventToarray($event);
                    }
                    $recurring_events[] = $event;
                }
            }
            if (!$recurring) {
                $edt = UNL_UCBCN::factory('eventdatetime');
                $edt->get('event_id', $e->id);
                $rtype = $edt->recurringtype;
                
                //2014-10-06 - events with dates set and marked as recurring but not recurring because the end date was the same as the start date were being hidden
                $events[] = $e;
                if ($rtype == 'none' || $rtype == '') {
                    //$events[] = $e;
                } else {
                    // no recurrences for this event are in this listing
                    //unset($listing->events[$key]);
                }
            }
        }
        // merge and sort recurring and non-recurring events
        while ($recurring_event = array_pop($recurring_events)) {
            $is_array = is_array($recurring_event);
            $recurring_event = UNL_UCBCN_Event::arrayToEvent($recurring_event);
            // eventdatetime info on the recurring event
            $rec_edt = UNL_UCBCN::factory('eventdatetime');
            // recurrence info on the recurring event
            $rec_rec = UNL_UCBCN::factory('recurringdate');
            $rec_edt->get('event_id', $recurring_event->id);
            $rec_rec->event_id = $rec_edt->event_id;
            $rec_rec->recurrence_id = $recurring_event->recurrence_id;
            $rec_rec->find(true);
            $rec_starttime = $rec_rec->recurringdate .
                                substr($rec_edt->starttime, 10);
            $inserted = false;
            // insert recurring events in order
            for ($i = 0; $i < count($events); $i++) {
                $edt  = UNL_UCBCN::factory('eventdatetime');
                $id = is_array($events[$i]) ? $events[$i]['id'] : $events[$i]->id;
                $edt->get('event_id', $id);
                $start = '';
                // get information about this event if it is recurring
                if (isset($events[$i]->recurrence_id)) {
                    $recurrence_id = is_array($events[$i]) ? $events[$i]['recurrence_id'] : $events[$i]->recurrence_id;
                    $rec  = UNL_UCBCN::factory('recurringdate');
                    $rec->event_id = $id;
                    $rec->recurrence_id = $events[$i]->recurrence_id;
                    $rec->find(true);
                    $start = $rec->recurringdate.substr($edt->starttime, 10);
                } else {
                    $start = $edt->starttime;
                }
                if (strtotime($rec_starttime) > strtotime($start)) {
                    if ($is_array) {
                        $recurring_event = UNL_UCBCN_Event::eventToArray($recurring_event); 
                    }
                    // insert event ahead of this location
                    $beg = array_splice($events, 0, $i);
                    $end = array_splice($events, 0);
                    $events = array_merge($beg, array($recurring_event), $end);
                    $inserted = true;
                    break;
                }
            }
            if (!$inserted) {
                if ($is_array) {
                    $recurring_event = UNL_UCBCN_Event::eventToArray($recurring_event); 
                }
                // insert at end
                $events[] = $recurring_event;
            }
        }
        $listing->events = $events;
       
        return $listing;
    }
    
    /**
     * Unlinks events from the recurringdate table, 
     * adding new events where necesary.
     * 
     * @param string $table    name of table that called this function
     * @param array  &$values  values passed from preProcessForm()
     * @param array  $datetime associative array of datetime values
     * 
     * @return void
     */
    public function unlinkEvents($table, &$values, $datetime)
    {
        if ($values['rec'] != 'this' && $values['rec'] != 'following') {
            return;
        }
        $event_id = ($table == 'event') ? $values['id'] : $values['event_id'];
        $rec = $values['rec'];
        $recid = $values['recid'];
        $calendar_has_event = UNL_UCBCN::factory('calendar_has_event');
        $event = UNL_UCBCN::factory('event');
        $eventdatetime = UNL_UCBCN::factory('eventdatetime');
        $recurringdate = UNL_UCBCN::factory('recurringdate');
        $calendar_has_event->whereAdd("event_id = $event_id");
        $calendar_has_event->find(true);
        $event->get($event_id);
        $eventdatetime->whereAdd("event_id = $event_id");
        $eventdatetime->find(true);
        $recurringdate->whereAdd("event_id = $event_id");
        $recurringdate->whereAdd("ongoing = FALSE");
        if ($rec == 'this') {
            $recurringdate->whereAdd("recurrence_id = $recid");
        }
        $recurringdate->find(true);
        $che = clone($calendar_has_event);
        $e = clone($event);
        $edt = clone($eventdatetime);
        $tables['event'] =& $e;
        $tables['eventdatetime'] =& $edt;
        // update the appropriate table with the passed-in values
        foreach ($values as $key => $value) {
            $tables[$table]->$key = $value;
        }
        $e->insert();
        // update event_id
        $edt->event_id = $e->id;
        // make sure datetime information is correct
        $edt->starttime = $datetime['starttime'];
        $edt->endtime = $datetime['endtime'];
        if ($rec == 'this') {
            // remove recurrence info
            $edt->recurringtype = 'none';
            $edt->recurs_until = '';
            $edt->rectypemonth = '';
        }
        $edt->insert();
        // add to calendar
        $che->event_id = $e->id;
        if ($che->status == 'archived') {
            $today = strtotime(date('Y-m-d'));
            $starttime = strtotime($datetime['starttime']);
            if ($starttime > $today) {
                $endtime = strtotime($datetime['endtime']);
                if (!$datetime['endtime'] || $endtime > $today) {
                    $che->status = 'posted';
                }
            }
        }
        $che->insert();
        // update recurringdate
        if ($rec == 'this') {
            $sql = "UPDATE recurringdate
                    SET unlinked=1
                    WHERE event_id = {$event_id}
                    AND recurrence_id = {$recurringdate->recurrence_id}";
            $recurringdate->query($sql);
        }
        
        // revert $values so other tables will not be affected
        $t = UNL_UCBCN::factory($table);
        $t->get($values['id']);
        foreach ($t as $key => $value) {
            $values[$key] = $value;
        }
        if ($rec == 'following') {
            // get previous date
            $recurringdate = UNL_UCBCN::factory('recurringdate');
            $recurringdate->event_id = $event_id;
            $recurringdate->recurrence_id = $recid - 1;
            $recurringdate->find(true);
            $previous = $recurringdate->recurringdate;
            if ($table == 'eventdatetime') {
                $values['recurs_until'] = $previous;
            } else {
                $eventdatetime->recurs_until = $previous;
                $eventdatetime->update();
            }
        }
    }
}
