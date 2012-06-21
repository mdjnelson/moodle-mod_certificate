<?php

/**
 * This page reviews a certificate
 *
 * @package    mod
 * @subpackage certificate
 * @copyright Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->libdir/pdflib.php");

// Retreive any variables that are passed
$id = required_param('id', PARAM_INT);    // Course Module ID
$action = optional_param('action', '', PARAM_ALPHA);

if (!$cm = get_coursemodule_from_id('certificate', $id)) {
    print_error('Course Module ID was incorrect');
}

if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error('course is misconfigured');
}

if (!$certificate = $DB->get_record('certificate', array('id'=> $cm->instance))) {
    print_error('course module is incorrect');
}

// Requires a course login
require_course_login($course->id, true, $cm);

// Check the capabilities
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/certificate:view', $context);

// Initialize $PAGE, compute blocks
$PAGE->set_url('/mod/certificate/review.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_title(format_string($certificate->name));
$PAGE->set_heading(format_string($course->fullname));

// Get previous cert record
if (!$certrecord = $DB->get_record('certificate_issues', array('userid' => $USER->id, 'certificateid' => $certificate->id))) {
    notice(get_string('nocertificatesissued', 'certificate'), "$CFG->wwwroot/course/view.php?id=$course->id");
    die;
}

// Load the specific certificatetype
require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

if ($action) {
    $filename = clean_filename($certificate->name.'.pdf');
    $pdf->Output($filename, 'I'); // open in browser
    exit();
}

echo $OUTPUT->header();

if (has_capability('mod/certificate:manage', $context)) {
    $numusers = count(certificate_get_issues($certificate->id, 'ci.timecreated ASC', '', $cm));
    $url = html_writer::tag('a', get_string('viewcertificateviews', 'certificate', $numusers),
        array('href' => $CFG->wwwroot . '/mod/certificate/report.php?id=' . $cm->id));
    echo html_writer::tag('div', $url, array('class' => 'reportlink'));
}

if (!empty($certificate->intro)) {
    echo $OUTPUT->box(format_module_intro('certificate', $certificate, $cm->id), 'generalbox', 'intro');
}

echo html_writer::tag('p', get_string('viewed', 'certificate'). '<br />' . userdate($certrecord->timecreated), array('style' => 'text-align:center'));

$link = new moodle_url('/mod/certificate/review.php?id='.$cm->id.'&action=get');
$linkname = get_string('reviewcertificate', 'certificate');
$button = new single_button($link, $linkname);
$button->add_action(new popup_action('click', $link, array('height' => 600, 'width' => 800)));

echo html_writer::tag('div', $OUTPUT->render($button), array('style' => 'text-align:center'));

echo $OUTPUT->footer($course);
