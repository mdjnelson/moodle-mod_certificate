<?php  // $Id: version.php,v 3.1.0

   require_once("../../config.php");
    require_once("lib.php");

    $id   = optional_param('id', 0, PARAM_INT);          // Course module ID
    $sort = optional_param('sort', '', PARAM_RAW);

    if (! $cm = get_coursemodule_from_id('certificate', $id)) {
            error("Course Module ID was incorrect");
        }

    if (! $course = get_record("course", "id", $cm->course)) {
        error("Course is misconfigured");
    }
    if (! $certificate = get_record("certificate", "id", $cm->instance)) {
        error("Certificate ID was incorrect");
    }
	
    require_login($course->id, false);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:manage', $context);

$strcertificates = get_string('modulenameplural', 'certificate');
$strcertificate  = get_string('modulename', 'certificate');
$strto = get_string('to', 'certificate');
$strdate = get_string('date', 'certificate');
$strcode = get_string('code', 'certificate');
$strreport= get_string('report', 'certificate');

add_to_log($course->id, "certificate", "view", "report.php?id=$cm->id", "$certificate->id", $cm->id);

    
 print_header_simple(format_string($certificate->name), "",
                 "<a href=\"index.php?id=$course->id\">$strcertificates</a> ->
                  <a href=\"view.php?id=$cm->id\">".format_string($certificate->name,true)."</a> -> $strreport",
                  "", "", true);
/*this isn't working--and needs updating for permissions
// Check to see if groups are being used 
        if ($groupmode = groupmode($course, $cm)) {   // Groups are being used
            $currentgroup = setup_and_print_groups($course, $groupmode, 'report.php?id='.$cm->id);
        } else {
            $currentgroup = 0;
        }

    /// Get all teachers and students
        if ($currentgroup) {
            $users = get_group_users($currentgroup);
        } else {
            $users = get_course_users($course->id);
        }
*/

        $sqlsort = "s.studentname ASC";
//or sort by date:
      // $sqlsort = "s.certdate ASC";  
if (!$users = certificate_get_issues($certificate->id, $USER, $sqlsort)) { 
    notice("There are no issued certificates", "../../course/view.php?id=$course->id");
    die;
}

$table->head  = array ($strto, $strdate, $strcode);
$table->align = array ("LEFT", "LEFT", "LEFT");

foreach ($users as $user) {
$name = print_user_picture($user->id, $course->id, $user->picture, false, true).$user->studentname;
$date = userdate($user->timecreated).certificate_print_user_files($user->id);
$code = $user->code;
$table->data[] = array ($name, $date, $code);
}


echo "<BR>";

print_table($table);

 print_footer($course);
?>