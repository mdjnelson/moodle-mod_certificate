<?PHP

function certificate_upgrade($oldversion) {
    global $CFG;

if ($oldversion < 2006101200) {
execute_sql(" ALTER TABLE `{$CFG->prefix}certificate` ADD `customtext` TEXT AFTER `printteacher`") ;
}
if ($oldversion < 2006081700) {
 execute_sql(" ALTER TABLE `{$CFG->prefix}certificate_issues` CHANGE `classname` `classname` VARCHAR( 254 )");
}   
    return true;
}

?>