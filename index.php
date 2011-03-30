<?PHP // $Id: index.php

/// This page lists all the instances of certificate in a particular course

    require_once('../../config.php');
    require_once('lib.php');
    global $DB;

    $id = required_param('id', PARAM_INT);           // Course Module ID

    if (! $course = $DB->get_record('course', array('id'=> $id))) {
        error('Course ID is incorrect');
    }

    require_course_login($course);
    $PAGE->set_pagelayout('incourse');
    add_to_log($course->id, 'certificate', 'view all', 'index.php?id='.$course->id, '');

/// Get all required strings
    $strcertificates = get_string('modulenameplural', 'certificate');
    $strcertificate  = get_string('modulename', 'certificate');

/// Print the header
$PAGE->set_url('/mod/certificate/index.php', array('id'=>$course->id));
$PAGE->navbar->add($strcertificates);
$PAGE->set_title($strcertificates);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

/// Get all the appropriate data
if (! $certificates = get_all_instances_in_course('certificate', $course)) {
    notice('There are no certificates', "../../course/view.php?id=$course->id");
    die;
}

$usesections = course_format_uses_sections($course->format);
if ($usesections) {
    $sections = get_all_sections($course->id);
}

/// Print the list of instances
$timenow = time();
$strname  = get_string("name");
$strsectionname = get_string('sectionname', 'format_'.$course->format);
$strissued  = get_string('issued', 'certificate');

$table = new html_table();

if ($usesections) {
    $table->head  = array ($strsectionname, $strname, $strissued);
} else {
    $table->head  = array ($strname, $strissued);
}

$currentsection = "";

foreach ($certificates as $certificate) {
    if (!$certificate->visible) {
        //Show dimmed if the mod is hidden
        $link = "<a class=\"dimmed\" href=\"view.php?id=$certificate->coursemodule\">$certificate->name</a>";
    } else {
        //Show normal if the mod is visible
        $link = "<a href=\"view.php?id=$certificate->coursemodule\">$certificate->name</a>";
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

    $sql = 'SELECT MAX(timecreated) AS latest FROM {certificate_issues} '.
                           'WHERE userid = '.$USER->id.' and certificateid = '.$certificate->id.'';
            if ($record = $DB->get_record_sql($sql)) {
                $latest = $record->latest;
            }
    $certrecord = $DB->get_record('certificate_issues', array('certificateid'=>$certificate->id, 'userid'=>$USER->id, 'timecreated'=>$latest));
    if($certrecord) {
        if($certrecord->certdate > 0) {
            $issued = userdate($certrecord->certdate);
        } else {
            $issued = get_string('notreceived', 'certificate');
        }
    } else {
        $issued = get_string('notreceived', 'certificate');
    }
    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($certificate->section, $link, $issued);
    } else {
        $table->data[] = array ($link, $issued);
    }
}
echo '<br />';

echo html_writer::table($table);

/// Finish the page

echo $OUTPUT->footer();
