<?PHP

function certificate_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2006081700) {
 execute_sql(" ALTER TABLE `{$CFG->prefix}certificate_issues` CHANGE `classname` `classname` VARCHAR( 254 )");
}

if ($oldversion < 2006101201) {
 execute_sql(" ALTER TABLE `{$CFG->prefix}certificate` CHANGE `save` `savecert` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '0'");
}     
    return true;
}

?>