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

if (($edit != -1) and $PAGE->user_allowed_editing()) {
     $USER->editing = $edit;
}

// Add block editing button
if ($PAGE->user_allowed_editing()) {
    $buttons = '<table><tr><td><form method="get" action="view.php"><div>'.
        '<input type="hidden" name="id" value="'.$cm->id.'" />'.
        '<input type="hidden" name="edit" value="'.($PAGE->user_is_editing()?'off':'on').'" />'.
        '<input type="submit" value="'.get_string($PAGE->user_is_editing()?'blockseditoff':'blocksediton').'" /></div></form></td></tr></table>';
    $PAGE->set_button($buttons);
}

// Create new certificate record
$certrecord = certificate_prepare_issue($course, $USER, $certificate);

// Load some strings
$strreviewcertificate = get_string('reviewcertificate', 'certificate');
$strgetcertificate = get_string('getcertificate', 'certificate');
$strgrade = get_string('grade', 'certificate');
$strcoursegrade = get_string('coursegrade', 'certificate');
$strcredithours = get_string('credithours', 'certificate');
$filename = clean_filename($certificate->name.'.pdf');

// Load the specific certificatetype
require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

if ($certificate->reissuecert) { // Reissue certificate every time
    if (empty($action)) {
        view_header($course, $certificate, $cm);
        if ($attempts = certificate_get_attempts($certificate->id, $USER->id)) {
            echo certificate_print_attempts($certificate->id, $USER->id);
        }
        if ($certificate->delivery == 0)    {
            echo '<p style="text-align:center">'.get_string('openwindow', 'certificate').'</p>';
        } elseif ($certificate->delivery == 1)    {
            echo '<p style="text-align:center">'.get_string('opendownload', 'certificate').'</p>';
        } elseif ($certificate->delivery == 2)    {
            echo '<p style="text-align:center">'.get_string('openemail', 'certificate').'</p>';
        }

        echo '<div style="text-align:center">';
        $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
        $linkname = $strgetcertificate;
        $button = new single_button($link, $linkname);
        $button->add_action(new popup_action('click', $link, 'view'.$cm->id, array('height' => 600, 'width' => 800)));
        echo $OUTPUT->render($button);
        echo '</div>';
        add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
        echo $OUTPUT->footer($course);
        exit;
    }
    certificate_issue($course, $certificate, $certrecord, $cm); // update certrecord as issued
} else if ($certrecord->certdate > 0) { // Review certificate
    if (empty($action)) {
        view_header($course, $certificate, $cm);
        $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
        echo '<p style="text-align:center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
        echo '<div style="text-align:center">';
        $linkname = $strreviewcertificate;
        $button = new single_button($link, $linkname);
        $button->add_action(new popup_action('click', $link, 'view'.$cm->id, array('height' => 600, 'width' => 800)));
        echo $OUTPUT->render($button);
        echo '</div>';
        echo $OUTPUT->footer($course);
        exit;
    }
} else if ($certrecord->certdate == 0) { //Create certificate
    if (empty($action)) {
        view_header($course, $certificate, $cm);
        if ($certificate->delivery == 0)    {
            echo '<p style="text-align:center">'.get_string('openwindow', 'certificate').'</p>';
        } elseif ($certificate->delivery == 1)    {
            echo '<p style="text-align:center">'.get_string('opendownload', 'certificate').'</p>';
        } elseif ($certificate->delivery == 2)    {
            echo '<p style="text-align:center">'.get_string('openemail', 'certificate').'</p>';
        }

        $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
        echo '<div style="text-align:center">';
        $linkname = $strgetcertificate;
        $button = new single_button($link, $linkname);
        $button->add_action(new popup_action('click', $link, 'view'.$cm->id, array('height' => 600, 'width' => 800)));
        echo $OUTPUT->render($button);
        echo '</div>';
        add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
        echo $OUTPUT->footer($course);
        exit;
    }
    certificate_issue($course, $certificate, $certrecord, $cm); // update certrecord as issued
}

if ($action) {
    // Output to pdf
    if ($certificate->savecert == 1) {
        //pdf contents are now in $file_contents as a string
       $file_contents = $pdf->Output('', 'S');
       $filename = clean_filename($certificate->name.'.pdf');
       certificate_save_pdf($file_contents, $certrecord->id, $filename, $context->id);
    }
    if ($certificate->delivery == 0) {
        $pdf->Output($filename, 'I');// open in browser
    } elseif ($certificate->delivery == 1) {
        $pdf->Output($filename, 'D'); // force download when create
    } elseif ($certificate->delivery == 2) {
        certificate_email_students($USER, $course, $certificate, $certrecord, $context);
        $pdf->Output($filename, 'I');// open in browser
        $pdf->Output('', 'S');// send
    }
}