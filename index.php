<?PHP // $Id: index.php,v 3.1.0

/// This page lists all the instances of certificate in a particular course

    require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT);           // Course Module ID

    if (! $course = get_record("course", "id", $id)) {
        error("Course ID is incorrect");
    }    

    require_course_login($course);
    add_to_log($course->id, 'certificate', 'view all', 'index.php?id='.$course->id, '');


/// Get all required strings
    $strcertificates = get_string('modulenameplural', 'certificate');
    $strcertificate  = get_string('modulename', 'certificate');


/// Print the header
print_header_simple($strcertificates, "", $strcertificates, "", "", true, "", navmenu($course));

/// Get all the appropriate data
if (! $certificates = get_all_instances_in_course("certificate", $course)) {
    notice("There are no certificates", "../../course/view.php?id=$course->id");
    die;
}

/// Print the list of instances
$timenow = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic  = get_string('topic');
$strissued  = get_string('issued', 'certificate');

if ($course->format == "weeks") {
    $table->head  = array ($strweek, $strname, $strissued);
    $table->align = array ("CENTER", "LEFT");
} else if ($course->format == "topics") {
    $table->head  = array ($strtopic, $strname, $strissued);
    $table->align = array ("CENTER", "LEFT", "LEFT", "LEFT");
} else {
    $table->head  = array ($strname, $strissued);
    $table->align = array ("LEFT", "LEFT", "LEFT");
}

$currentgroup = get_current_group($course->id);
    if ($currentgroup and isteacheredit($course->id)) {
        $group = get_record("groups", "id", $currentgroup);
        $groupname = " ($group->name)";
    } else {
        $groupname = "";
    }

    $currentsection = "";


foreach ($certificates as $certificate) {
    if (!$certificate->visible) {
        //Show dimmed if the mod is hidden
        $link = "<A class=\"dimmed\" HREF=\"view.php?id=$certificate->coursemodule\">$certificate->name</A>";
    } else {
        //Show normal if the mod is visible
        $link = "<A HREF=\"view.php?id=$certificate->coursemodule\">$certificate->name</A>";
    }


 $printsection = "";
        if ($certificate->section !== $currentsection) {
            if ($certificate->section) {
                $printsection = $certificate->section;
            }
            if ($currentsection !== "") {
                $table->data[] = 'hr';
            }
            $currentsection = $certificate->section;
        }

$certrecord = certificate_get_issue($course, $USER);
if($certrecord) {
$issued = userdate($certrecord->timecreated);
} else 
$issued = get_string('notreceived', 'certificate');

    if ($course->format == "weeks" or $course->format == "topics") {
        $table->data[] = array ($certificate->section, $link, $issued);
    } else {
        $table->data[] = array ($link, $issued);
    }
}
echo "<BR>";

print_table($table);

/// Finish the page

print_footer($course);

?>