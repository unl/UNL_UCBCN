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
        $rd->find();
        
        $res = array();
        
        while($rd->fetch()) {
            $res[] = date('m-d', strtotime($rd->recurringdate));
        }
        
        return $res;
    }
    
    /**
     * Updates table containing all dates with recurring events.
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
        // Events with a negative recurrence_id will be considered unlinked.
        //$sql    =  "SELECT id, recurrence_id FROM recurringdate;";
        //$ule    =& $db->queryCol($sql);
        
        // [0] => recurringdate, [1] => event_id, [2] => recurrence_id, [3] => ongoing
        $res = array(array(), array(), array(), array());
        for ($i = 0, $j = 0, $k = 0, $r = 0; $i < count($days); $i++, $k=0) {
            $cur = $days[$i];
            while (strtotime($cur) <= strtotime($rcu[$i])) {
                $sql = "SELECT recurrence_id FROM recurringdate
                        WHERE event_id={$eid[$i]}
                            AND (recurrence_id = 0
                            OR recurrence_id = -1);";
                $res[0][$j] = date('Y-m-d', strtotime($cur));
                $res[1][$j] = $eid[$i];
                $res[2][$j] = $k;
                $res[3][$j++] = 'FALSE';
                $temp = date('D Y-m-d H:i:s', strtotime('next day', strtotime($cur)));
                while (strtotime($temp) <= strtotime($end[$i])) {
                    $res[0][$j] = date('Y-m-d', strtotime($temp));
                    $res[1][$j] = $eid[$i];
                    $res[2][$j] = $k;
                    $res[3][$j++] = 'TRUE';
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
            
        }
        
        // Clean this month
        $sql = "DROP TABLE IF EXISTS recurringdate;";
        $dropres = $db->query($sql);
        $sql    = "CREATE TABLE IF NOT EXISTS `recurringdate` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `recurringdate` DATE NOT NULL, 
                  `event_id` INTEGER(10) UNSIGNED NOT NULL,
                  `recurrence_id` INTEGER(10) UNSIGNED NOT NULL,
                  `ongoing` BOOL, PRIMARY KEY (`id`));";
        
        $table  =& $db->query($sql);
        if (!PEAR::isError($table)) {
            for ($i = 0; $i < count($res[0]); $i++) {
                $sql = "INSERT INTO recurringdate (recurringdate, event_id, recurrence_id, ongoing) 
                        VALUES('{$res[0][$i]}', {$res[1][$i]}, {$res[2][$i]}, {$res[3][$i]});";
                $ret = $db->query($sql);
                $res[0][$i] = date('m-d', strtotime($res[0][$i]));
            }
        }
    }
    
    /**
     * Takes an event_id and turns every recurrence of that event into
     * an independent event.
     * 
     * @param int id of event to clone $event_id
     */
    public function cloneRecurrences($event_id) {
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
        $recurringdate->find();
        $dates = array();
        //$rec_ids = array();
        $is_ongoing = array();
        while ($recurringdate->fetch()) {
            $dates[] = $recurringdate->recurringdate;
            //$rec_ids[] = $recurringdate->recurrence_id;
            $is_ongoing[] = $recurringdate->ongoing;
        }
        for ($i = 0; $i < count($dates); $i++) {
            if ($is_ongoing[$i]) {
                continue;
            }
            $che = clone($calendar_has_event);
            $e = clone($event);
            $edt = clone($eventdatetime);
            $e->insert();
            // update event_id
            $edt->event_id = $e->id;
            // update starttime and endtime
            $starttime = strtotime($edt->starttime);
            $endtime = strtotime($edt->endtime);
            $diff = strtotime($dates[$i]) - $starttime;
            $edt->starttime = date('Y-m-d H:i:s', $diff + $starttime);
            $edt->endtime = date('Y-m-d H:i:s', $diff + $endtime);
            // remove recurrence info
            $edt->recurringtype = 'none';
            $edt->recurs_until = '';
            $edt->rectypemonth = '';
            $edt->insert();
            // add to calendar
            $che->event_id = $e->id;
            $che->insert();
        }
        // remove original event
        $calendar_has_event->delete();
        $event->delete();
        $eventdatetime->delete();
    }
}