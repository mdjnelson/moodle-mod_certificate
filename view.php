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

// log update
    add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);
    $completion=new completion_info($course);
    $completion->set_module_viewed($cm);

// Initialize $PAGE, compute blocks
    $PAGE->set_url('/mod/certificate/view.php', array('id' => $cm->id));
    $PAGE->set_context($context);
    $PAGE->set_cm($cm);

/// Create new certrecord
    certificate_prepare_issue($course, $USER, $certificate);

/// Get previous certrecord
    $certificateid = $certificate->id;
    $sql = 'SELECT MAX(timecreated) AS latest FROM {certificate_issues} '.
                           'WHERE userid = '.$USER->id.' and certificateid = '.$certificate->id.' and certdate > 0';
            if ($record = $DB->get_record_sql($sql)) {
                $latest = $record->latest;
            }
    $certrecord = $DB->get_record('certificate_issues', array('certificateid'=>$certificateid, 'userid'=>$USER->id, 'certdate'=>'0'));
    $lastcertrecord = $DB->get_record('certificate_issues', array('certificateid'=>$certificateid, 'userid'=>$USER->id, 'timecreated'=>$latest));

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

    if($certificate->reissuecert) { ///Reissue certificate every time
        if(empty($action)) {
            view_header($course, $certificate, $cm);
			if ($lastcertrecord){
            echo '<p align="center">'.get_string('lastviewed', 'certificate').'<br />'.userdate($lastcertrecord->certdate).'</p>';
            echo '<center>';
            $link = new moodle_url('/mod/certificate/review.php?id='.$cm->id.'&action=review');
            $linkname = $strreviewcertificate;
            $button = new single_button($link, $linkname);
            $button->add_action(new popup_action('click', $link, array('height' => 600, 'width' => 800)));
            echo $OUTPUT->render($button);
            echo '</center>';
			}
            echo '<p align="center">'.get_string('or', 'certificate').'<br /></p>';

            if ($certificate->delivery == 0)    {
                echo '<p align="center">'.get_string('openwindow', 'certificate').'</p>';
            } elseif ($certificate->delivery == 1)    {
                echo '<p align="center">'.get_string('opendownload', 'certificate').'</p>';
            } elseif ($certificate->delivery == 2)    {
                echo '<p align="center">'.get_string('openemail', 'certificate').'</p>';
            }

            echo '<center>';
            $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
			$linkname = $strgetcertificate;
            $button = new single_button($link, $linkname);
            $button->add_action(new popup_action('click', $link, array('height' => 600, 'width' => 800)));
            echo $OUTPUT->render($button);
            echo '</center>';
            add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
            echo $OUTPUT->footer($course);
            exit;
        }
        certificate_issue($course, $USER, $certificate, $certrecord, $cm); // update certrecord as issued
} else if ($lastcertrecord) { ///Review certificate
        if (empty($action)) {
            view_header($course, $certificate, $cm);
            $link = new moodle_url('/mod/certificate/review.php?id='.$cm->id.'&action=review');
            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($lastcertrecord->certdate).'</p>';
            echo '<center>';
			$linkname = $strreviewcertificate;
            $button = new single_button($link, $linkname);
            $button->add_action(new popup_action('click', $link, array('height' => 600, 'width' => 800)));
            echo $OUTPUT->render($button);
            echo '</center>';

            echo $OUTPUT->footer($course);
            exit;
        }
	} else { ///Create certificate
        if(empty($action)) {
            view_header($course, $certificate, $cm);
            if ($certificate->delivery == 0)    {
                echo '<p align="center">'.get_string('openwindow', 'certificate').'</p>';
            } elseif ($certificate->delivery == 1)    {
                echo '<p align="center">'.get_string('opendownload', 'certificate').'</p>';
            } elseif ($certificate->delivery == 2)    {
                echo '<p align="center">'.get_string('openemail', 'certificate').'</p>';
            }
            echo '<center>';
            $link = new moodle_url('/mod/certificate/view.php?id='.$cm->id.'&action=get');
            $linkname = $strgetcertificate;
            $action = new popup_action('click', $link, array('height' => 600, 'width' => 800));
            $popup = $OUTPUT->action_link($link, $linkname, $action, array('title'=>$linkname));
            echo $popup;
            echo '</center>';
            add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
            echo $OUTPUT->footer($course);
            exit;
        }
		}
        certificate_issue($course, $certificate, $certrecord, $cm); // update certrecord as issued

// Output to pdf this needs to be fixed
 //   certificate_file_area($USER->id);
  //  $file = $CFG->dataroot.'/'.$course->id.'/moddata/certificate/'.$certificate->id.'/'.$USER->id.'/'.$filename;

 //   if ($certificate->savecert == 1){
 //       $pdf->Output($file, 'F');//save as file
  //  }
    if ($certificate->delivery == 0){
        $pdf->Output($filename, 'I');// open in browser
    } elseif ($certificate->delivery == 1){
        $pdf->Output($filename, 'D'); // force download when create
    } elseif ($certificate->delivery == 2){
        certificate_email_students($USER, $course, $certificate, $certrecord);
        $pdf->Output($filename, 'I');// open in browser
        $pdf->Output('', 'S');// send
    }
