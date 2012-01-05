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

// Declare some variables
$strreviewcertificate = get_string('reviewcertificate', 'certificate');
$strgetcertificate = get_string('getcertificate', 'certificate');
$strgrade = get_string('grade', 'certificate');
$strcoursegrade = get_string('coursegrade', 'certificate');
$strcredithours = get_string('credithours', 'certificate');
$filename = clean_filename($certificate->name.'.pdf');

// Initialize $PAGE, compute blocks
$PAGE->set_url('/mod/certificate/review.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);

// Get previous cert record
if (!$certrecord = certificate_get_latest_issue($certificate->id, $USER->id)) {
    notice(get_string('nocertificatesissued', 'certificate'), "$CFG->wwwroot/course/view.php?id=$course->id");
    die;
}

// Load the specific certificatetype
require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

if (empty($action)) {
    view_header($course, $certificate, $cm);
    if ($certrecord->certdate > 0) { // Review certificate
        if (empty($action)) {
            view_header($course, $certificate, $cm);
            $link = new moodle_url('/mod/certificate/review.php?id='.$cm->id.'&action=get');
            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
            echo '<center>';
            $linkname = $strreviewcertificate;
            $button = new single_button($link, $linkname);
            $button->add_action(new popup_action('click', $link, array('height' => 600, 'width' => 800)));
            echo $OUTPUT->render($button);
            echo '</center>';

            echo $OUTPUT->footer($course);
            exit;
        }
    }
}
$pdf->Output($filename, 'I'); // open in browser