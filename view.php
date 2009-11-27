<?PHP // $Id: version.php

require_once('../../config.php');
require_once('lib.php');
include '../../lib/pdflib.php';

    $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '', PARAM_ALPHA);
    $edit = optional_param('edit', -1, PARAM_BOOL);
    $page = optional_param('page', 0, PARAM_INT);

    if (! $cm = get_coursemodule_from_id('certificate', $id)) {
        error('Course Module ID was incorrect');
    }
    if (! $course = $DB->get_record('course', array('id'=> $cm->course))) {
        error('course is misconfigured');
    }
    if (! $certificate = $DB->get_record('certificate', array('id'=> $cm->instance))) {
        error('course module is incorrect');
    }

    global $USER, $DB;
    require_login($course->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:view', $context);

// log update
    add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);

// Initialize $PAGE, compute blocks
    $PAGE->set_url('mod/certificate/view.php', array('id' => $cm->id));

    if (($edit != -1) and $PAGE->user_allowed_editing()) {
        $USER->editing = $edit;
    }

/// Create new certrecord
    certificate_prepare_issue($course, $USER, $certificate);

/// Get previous certrecord
    $certificateid = $certificate->id;
    $sql = 'SELECT MAX(timecreated) AS latest FROM {certificate_issues} '.
                           'WHERE userid = '.$USER->id.' and certificateid = '.$certificate->id.'';
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

    if($certificate->reissuecert) { ///Reissue certificate every time
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
            link_to_popup_window ('/mod/certificate/view.php?id='.$cm->id.'&action=get', '', $strgetcertificate, 600, 800, $strgetcertificate);
            echo '</center>';
            add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
            echo $OUTPUT->footer($course);
            exit;
        }
        certificate_issue($course, $USER, $certificate, $certrecord, $cm); // update certrecord as issued
    } else if ($certrecord->certdate > 0) { ///Review certificate
        if (empty($action)) {
            view_header($course, $certificate, $cm);
            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
            echo '<center>';
            $url = '/mod/certificate/view.php?id='.$cm->id.'&action=get';
            $linkname = $strreviewcertificate;
            $name = 'popup';
            $options= array('width' => 600, 'height' => 800);
            $link = html_link::make($url, $linkname);
            $link->add_action(new popup_action('click', $link->url, $name, $options));
            echo $OUTPUT->link($link);
            echo '</center>';

            echo $OUTPUT->footer($course);
            exit;
        }
    } else if ($certrecord->certdate == 0) { ///Create certificate
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
            $url = '/mod/certificate/view.php?id='.$cm->id.'&action=get';
            $linkname = $strgetcertificate;
            $name = 'popup';
            $options= array('width' => 600, 'height' => 800);
            $link = html_link::make($url, $linkname);
            $link->add_action(new popup_action('click', $link->url, $name, $options));
            echo $OUTPUT->link($link);
            echo '</center>';
            add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
            echo $OUTPUT->footer($course);
            exit;
        }
        certificate_issue($course, $certificate, $certrecord, $cm); // update certrecord as issued
    }
// Output to pdf
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
?>