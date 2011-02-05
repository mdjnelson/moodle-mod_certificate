<?PHP // $Id: version.php

require_once('../../config.php');
require_once('lib.php');
include '../../lib/pdflib.php';

    $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '', PARAM_ALPHA);

    if (! $cm = get_coursemodule_from_id('certificate', $id)) {
        error('Course Module ID was incorrect');
    }
    if (! $course = $DB->get_record('course', array('id'=> $cm->course))) {
        error('course is misconfigured');
    }
    if (! $certificate = $DB->get_record('certificate', array('id'=> $cm->instance))) {
        error('course module is incorrect');
    }

    require_login($course->id, true, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:view', $context);

// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/certificate/view.php', array('id' => $cm->id));
    $PAGE->set_context($context);
    $PAGE->set_cm($cm);

/// Get previous certrecord
    $certificateid = $certificate->id;
    $sql = 'SELECT MAX(timecreated) AS latest FROM {certificate_issues} '.
                           'WHERE userid = '.$USER->id.' and certificateid = '.$certificate->id.' and certdate > 0';
            if ($record = $DB->get_record_sql($sql)) {
                $latest = $record->latest;
            }
    $certrecord = $DB->get_record('certificate_issues', array('certificateid'=>$certificateid, 'userid'=>$USER->id, 'timecreated'=>$latest));

/// Load custom type
    $type = $certificate->certificatetype;

/// Load some strings
    $strreviewcertificate = get_string('reviewcertificate', 'certificate');
    $strgetcertificate = get_string('getcertificate', 'certificate');
    $strgrade = get_string('grade', 'certificate');
    $strcoursegrade = get_string('coursegrade', 'certificate');
    $strcredithours = get_string('credithours', 'certificate');
    $filename = clean_filename($certificate->name.'.pdf');

///Load the specific certificatetype
    require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

        if(empty($action)) {
            view_header($course, $certificate, $cm);
        if ($certrecord->certdate > 0) { ///Review certificate
        if (empty($action)) {
            view_header($course, $certificate, $cm);
            $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
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
        $pdf->Output($filename, 'I');// open in browser
