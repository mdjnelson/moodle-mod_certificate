<?php

/**
 * This page lists all the instances of certificate in a particular course
 *
 * @package    mod
 * @subpackage certificate
 * @copyright Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);           // Course Module ID

// Ensure that the course specified is valid
if (!$course = $DB->get_record('course', array('id'=> $id))) {
    print_error('Course ID is incorrect');
}

// Requires a login
require_course_login($course);

// Declare variables
$currentsection = "";
$printsection = "";
$timenow = time();

// Strings used multiple times
$strcertificates = get_string('modulenameplural', 'certificate');
$strissued  = get_string('issued', 'certificate');
$strname  = get_string("name");
$strsectionname = get_string('sectionname', 'format_'.$course->format);

// Print the header
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/mod/certificate/index.php', array('id'=>$course->id));
$PAGE->navbar->add($strcertificates);
$PAGE->set_title($strcertificates);
$PAGE->set_heading($course->fullname);

// Add the page view to the Moodle log
add_to_log($course->id, 'certificate', 'view all', 'index.php?id='.$course->id, '');

// Get the certificates, if there are none display a notice
if (!$certificates = get_all_instances_in_course('certificate', $course)) {
    echo $OUTPUT->header();
    notice(get_string('nocertificates', 'certificate'), "$CFG->wwwroot/course/view.php?id=$course->id");
    echo $OUTPUT->footer();
    exit();
}

if ($usesections = course_format_uses_sections($course->format)) {
    $sections = get_all_sections($course->id);
}

$table = new html_table();

if ($usesections) {
    $table->head  = array ($strsectionname, $strname, $strissued);
} else {
    $table->head  = array ($strname, $strissued);
}

foreach ($certificates as $certificate) {
    if (!$certificate->visible) {
        // Show dimmed if the mod is hidden
        $link = html_writer::tag('a', $certificate->name, array('class' => 'dimmed',
            'href' => $CFG->wwwroot . '/mod/certificate/view.php?id=' . $certificate->coursemodule));
    } else {
        // Show normal if the mod is visible
        $link = html_writer::tag('a', $certificate->name, array('class' => 'dimmed',
            'href' => $CFG->wwwroot . '/mod/certificate/view.php?id=' . $certificate->coursemodule));
    }
    if ($certificate->section !== $currentsection) {
        if ($certificate->section) {
            $printsection = $certificate->section;
        }
        if ($currentsection !== "") {
            $table->data[] = 'hr';
        }
        $currentsection = $certificate->section;
    }
    // Get the latest certificate issue
    if ($certrecord = $DB->get_record('certificate_issues', array('userid' => $USER->id, 'certificateid' => $certificate->id))) {
        $issued = userdate($certrecord->timecreated);
    } else {
        $issued = get_string('notreceived', 'certificate');
    }
    if (($course->format == 'weeks') || ($course->format == 'topics')) {
        $table->data[] = array ($certificate->section, $link, $issued);
    } else {
        $table->data[] = array ($link, $issued);
    }
}

echo $OUTPUT->header();
echo '<br />';
echo html_writer::table($table);
echo $OUTPUT->footer();