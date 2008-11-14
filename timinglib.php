<?php
function tl_get_course_time ($courseid, $userid) {
    global $CFG;

    set_time_limit (0);

    //totalcount is passed by reference
    $sql_log = 'l.course = '.$courseid.' AND l.userid = '.$userid;
    $logs = get_logs ($sql_log, 'l.time ASC', '', '', $totalcount);

    if (!is_array ($logs)) return 0;

    $totaltime = 0;
    foreach ($logs as $log) {
         if (!isset($login)) {
             // for the first time $login is not set so the first log is also
             // the first $login
             $login = $log->time;
             $last_hit = $log->time;
             $totaltime = 0;
         }
         $delay = $log->time - $last_hit;
         if ($delay > ($CFG->sessiontimeout * 60)) {
             // the difference between the last log and the current log is more than
             // the timeout Register session value so that we have found a session!
             $login = $log->time;
         } else {
             $totaltime += $delay;
         }
         // now the actual log became the previous log for the next cycle
         $last_hit = $log->time;
    }
    return $totaltime;
}
?>