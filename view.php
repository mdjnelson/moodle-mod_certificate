<?php

/**
 * Handles viewing a certificate
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Chardelle Busch, Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->libdir/pdflib.php");

$id = required_param('id', PARAM_INT);    // Course Module ID
$action = optional_param('action', '', PARAM_ALPHA);
$edit = optional_param('edit', -1, PARAM_BOOL);

if (!$cm = get_coursemodule_from_id('certificate', $id)) {
    print_error('Course Module ID was incorrect');
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error('course is misconfigured');
}
if (!$certificate = $DB->get_record('certificate', array('id'=> $cm->instance))) {
    print_error('course module is incorrect');
}

require_login($course->id, false, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/certificate:view', $context);

// log update
add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);
$completion=new completion_info($course);
$completion->set_module_viewed($cm);

// Initialize $PAGE, compute blocks
$PAGE->set_url('/mod/certificate/view.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_title(format_string($certificate->name));
$PAGE->set_heading(format_string($course->fullname));

// Set the context
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

if (($edit != -1) and $PAGE->user_allowed_editing()) {
     $USER->editing = $edit;
}

// Add block editing button
if ($PAGE->user_allowed_editing()) {
    $editvalue = $PAGE->user_is_editing() ? 'off' : 'on';
    $strsubmit = $PAGE->user_is_editing() ? get_string('blockseditoff') : get_string('blocksediton');
    $url = new moodle_url($CFG->wwwroot . '/mod/certificate/view.php', array('id' => $cm->id, 'edit' => $editvalue));
    $PAGE->set_button($OUTPUT->single_button($url, $strsubmit));
}

// Create new certificate record, or return existing record
$certrecord = certificate_get_issue($course, $USER, $certificate, $cm);

// Load the specific certificatetype
require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

if (empty($action)) { // Not displaying PDF
    echo $OUTPUT->header();

    /// find out current groups mode
    groups_print_activity_menu($cm, $CFG->wwwroot . '/mod/certificate/view.php?id=' . $cm->id);
    $currentgroup = groups_get_activity_group($cm);
    $groupmode = groups_get_activity_groupmode($cm);

    if (has_capability('mod/certificate:manage', $context)) {
        $numusers = count(certificate_get_issues($certificate->id, 'ci.timecreated ASC', $groupmode, $cm));
        $url = html_writer::tag('a', get_string('viewcertificateviews', 'certificate', $numusers),
            array('href' => $CFG->wwwroot . '/mod/certificate/report.php?id=' . $cm->id));
        echo html_writer::tag('div', $url, array('class' => 'reportlink'));
    }

    if (!empty($certificate->intro)) {
        echo $OUTPUT->box(format_module_intro('certificate', $certificate, $cm->id), 'generalbox', 'intro');
    }

    if ($attempts = certificate_get_attempts($certificate->id)) {
        echo certificate_print_attempts($course, $certificate, $attempts);
    }
    if ($certificate->delivery == 0)    {
        $str = get_string('openwindow', 'certificate');
    } elseif ($certificate->delivery == 1)    {
        $str = get_string('opendownload', 'certificate');
    } elseif ($certificate->delivery == 2)    {
        $str = get_string('openemail', 'certificate');
    }
    echo html_writer::tag('p', $str, array('style' => 'text-align:center'));
    $linkname = get_string('getcertificate', 'certificate');
    // Add to log, only if we are reissuing
    add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);

    $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
    $button = new single_button($link, $linkname);
    $button->add_action(new popup_action('click', $link, 'view'.$cm->id, array('height' => 600, 'width' => 800)));

    echo html_writer::tag('div', $OUTPUT->render($button), array('style' => 'text-align:center'));
    echo $OUTPUT->footer($course);
    exit;
} else { // Output to pdf
    // The PDF filename
    $filename = clean_filename($certificate->name.'.pdf');
    if ($certificate->savecert == 1) {
        // PDF contents are now in $file_contents as a string
       $file_contents = $pdf->Output('', 'S');
       certificate_save_pdf($file_contents, $certrecord->id, $filename, $context->id);
    }
    if ($certificate->delivery == 0) {
        $pdf->Output($filename, 'I'); // open in browser
    } elseif ($certificate->delivery == 1) {
        $pdf->Output($filename, 'D'); // force download when create
    } elseif ($certificate->delivery == 2) {
        certificate_email_student($course, $certificate, $certrecord, $context);
        $pdf->Output($filename, 'I'); // open in browser
        $pdf->Output('', 'S'); // send
    }
}
