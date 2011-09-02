<?php
class UNL_UCBCN_Recurringdate extends UNL_UCBCN_Record 
{

    public $id;                              // int(10)  not_null primary_key unsigned auto_increment
    public $recurringdate;                   // date(10)  not_null binary
    public $event_id;                        // int(10)  not_null unsigned
    public $recurrence_id;                   // int(10)  not_null unsigned
    public $ongoing;                         // int(1)  
    public $unlinked;                        // int(1)

    public function getTable()
    {
        return 'recurringdate';
    }

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
     * @param Calendar_Month_Weekdays $month Month to find events in.
     * 
     * @return an array with values representing the days with recurring events.
     */
    public function getRecurringDates($month)
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
        $rd->find();
        
        $res = array();
        
        while ($rd->fetch()) {
            $res[] = date('m-d', strtotime($rd->recurringdate));
        }
        
        return $res;
    }
    
    /**
     * Updates table containing all dates with recurring events.
     * 
     * @return void
     */
    public function updateRecurringEvents()
    {
        
        $recurrence = array('daily'    => '+1 day',
                            'weekly'   => '+1 week',
                            'monthly'  => '+1 month',
                            'annually' => '+1 year');
        
        $db     =& $this->getDatabaseConnection();
        $sql    = "SELECT DATE_FORMAT(starttime,'%a %Y-%m-%d %T') AS day,
                        event_id, recurringtype, recurs_until
                    FROM eventdatetime WHERE recurringtype != 'none' GROUP BY starttime;";
        $days   =& $db->queryCol($sql);
        $sql    = "SELECT DATE_FORMAT(endtime, '%a %Y-%m-%d %T'), starttime
                   FROM eventdatetime WHERE recurringtype != 'none' GROUP BY starttime;";
        $end    =& $db->queryCol($sql);
        $sql    = "SELECT event_id, starttime FROM eventdatetime
                   WHERE recurringtype != 'none' GROUP BY starttime;";
        $eid    =& $db->queryCol($sql);
        $sql    = "SELECT rectypemonth, starttime FROM eventdatetime
                   WHERE recurringtype != 'none' GROUP BY starttime;";
        $rtm    =& $db->queryCol($sql);
        $sql    = "SELECT recurringtype, starttime FROM eventdatetime
                   WHERE recurringtype != 'none' GROUP BY starttime;";
        $rct    =& $db->queryCol($sql);
        $sql    = "SELECT DATE_FORMAT(recurs_until, '%a %Y-%m-%d %T'), starttime 
                   FROM eventdatetime WHERE recurringtype != 'none' GROUP BY starttime;";
        $rcu    =& $db->queryCol($sql);
        
        // [0] => recurringdate, [1] => event_id, [2] => recurrence_id, [3] => ongoing, [4] => unlinked
        $res = array(array(), array(), array(), array());
        for ($i = 0, $j = 0, $k = 0, $r = 0; $i < count($days); $i++, $k=0) {
            $cur = $days[$i];
            $sql = "SELECT recurringdate FROM recurringdate
                    WHERE event_id={$eid[$i]} AND unlinked = TRUE;";
            $ule =& $db->queryCol($sql);
            while (strtotime($cur) <= strtotime($rcu[$i])) {
                $res[0][$j] = date('Y-m-d', strtotime($cur));
                $res[1][$j] = $eid[$i];
                $res[2][$j] = $k;
                $res[3][$j] = 'FALSE';
                $res[4][$j] = 'FALSE';
                $j++;
                $temp = date('D Y-m-d H:i:s', strtotime('next day', strtotime($cur)));
                while (strtotime($temp) <= strtotime($end[$i])) {
                    $res[0][$j] = date('Y-m-d', strtotime($temp));
                    $res[1][$j] = $eid[$i];
                    $res[2][$j] = $k;
                    $res[3][$j] = 'TRUE';
                    $res[4][$j] = 'FALSE' ;
                    $j++;
                    $temp = date('D Y-m-d H:i:s', strtotime('next day', strtotime($temp)));
                }
                $k++;
                if ($rct[$i] != 'monthly' || $rtm[$i] == 'date') {
                    $cur = date('D Y-m-d H:i:s', strtotime($recurrence[$rct[$i]], strtotime($cur)));
                    $end[$i] = date('D Y-m-d H:i:s', strtotime($recurrence[$rct[$i]], strtotime($end[$i])));
                } else if ($rtm[$i] == 'lastday') { 
                    $nextmon = date('F Y H:i:s', strtotime('+1 day', strtotime($cur)));
                    $nextmon = date('F Y H:i:s', strtotime('+1 month', strtotime($nextmon)));
                    $cur = date('D Y-m-d H:i:s', strtotime('-1 day', strtotime($nextmon)));
                } else {
                    // Update current
                    $weekday = date('l', strtotime($cur));
                    $fstr = ($rtm[$i] == 'last') ? '+2 months': 'next month';
                    $nextmon = date('F Y H:i:s', strtotime($fstr, strtotime($cur)));
                    $nextmonweekday = date('l', strtotime($nextmon));
                    $cur = date('D Y-m-d H:i:s', strtotime("{$rtm[$i]} $weekday, $nextmon")); 
                    if ($weekday == $nextmonweekday && $rtm[$i] != 'last') {
                        $cur = date('D Y-m-d H:i:s', strtotime('last week', strtotime($cur)));
                    }
                    // Update end
                    $weekday = date('l', strtotime($end[$i]));
                    $nextmon = date('F Y H:i:s', strtotime('next month', strtotime($end[$i])));
                    $nextmonweekday = date('l', strtotime($nextmon));
                    $end[$i] = date('D Y-m-d H:i:s', strtotime("{$rtm[$i]} $weekday, $nextmon"));
                    if ($weekday == $nextmonweekday && $rtm[$i] != 'last') {
                        $end[$i] = date('D Y-m-d H:i:s', strtotime('last week', strtotime($end[$i])));
                    }
                }
            }
            foreach ($ule as $unlinked_date) {
                if ($keys = array_keys($res[0], $unlinked_date)) {
                    foreach ($keys as $key) { 
                        $res[4][$key] = 'TRUE';
                    }
                }
            }
        }
        
        // Clean this month
        $sql = "DROP TABLE IF EXISTS recurringdate;";
        $dropres = $db->query($sql);
        $sql    = "CREATE TABLE IF NOT EXISTS `recurringdate` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `recurringdate` DATE NOT NULL, 
                  `event_id` INTEGER(10) UNSIGNED NOT NULL,
                  `recurrence_id` INTEGER(10) UNSIGNED NOT NULL,
                  `ongoing` BOOL,
                  `unlinked` BOOL DEFAULT FALSE, PRIMARY KEY (`id`));";
        
        $table  =& $db->query($sql);
        if (!PEAR::isError($table)) {
            for ($i = 0; $i < count($res[0]); $i++) {
                $sql = "INSERT INTO recurringdate (recurringdate, event_id, recurrence_id, ongoing, unlinked) 
                        VALUES('{$res[0][$i]}', {$res[1][$i]}, {$res[2][$i]}, {$res[3][$i]}, {$res[4][$i]});";
                $ret = $db->query($sql);
                //$res[0][$i] = date('m-d', strtotime($res[0][$i]));
            }
        }
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
                if ($rtype == 'none' || $rtype == '') {
                    $events[] = $e;
                } else {
                    // no recurrences for this event are in this listing
                    unset($listing->events[$key]);
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