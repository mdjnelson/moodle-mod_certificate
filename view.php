<?PHP // $Id: version.php,v 3.1.0

    require_once('../../config.php');
    require_once($CFG->dirroot . '/mod/certificate/lib.php');
    require_once($CFG->libdir . '/fpdf/fpdf.php');
    require_once($CFG->libdir . '/fpdf/fpdfprotection.php');
    include_once($CFG->dirroot . '/mod/certificate/html2pdf.php');

    $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '', PARAM_ALPHA);

    if (! $cm = get_coursemodule_from_id('certificate', $id)) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
        print_error('coursemisconf');
    }
    if (! $certificate = $DB->get_record("certificate", array("id" => $cm->instance))) {
        print_error('invalidcoursemodule');
    }

    global $USER;
    require_course_login($course, true, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:view', $context);

// log update
    add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);

/// Create certrecord
    certificate_prepare_issue($course, $USER, $certificate);

/// Load custom type
    $type = $certificate->certificatetype;
    $certificateid = $certificate->id;
    $certrecord = $DB->get_record('certificate_issues', array('certificateid' => $certificateid, 'userid' => $USER->id));

/// Load some strings
    $strreviewcertificate = get_string('reviewcertificate', 'certificate');
    $strgetcertificate = get_string('getcertificate', 'certificate');
    $strgrade = get_string('grade', 'certificate');
    $strcoursegrade = get_string('coursegrade', 'certificate');
    $strcredithours = get_string('credithours', 'certificate');

///Load the specific certificatetype
    require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

    if($certrecord->certdate > 0) { ///Review certificate
        if (empty($action)) {
            certificate_view_header($course, $certificate, $cm);

            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
            echo '<center>';
            $opt = new stdclass();
            $opt->id = $cm->id;
            $opt->action = 'review';
            echo '<form action="" method="get" name="form1" target="certframe">';
            print_single_button('', $opt, $strreviewcertificate, 'get', 'certframe');
            echo '</form>';
            echo '<iframe name="certframe" id="certframe" frameborder="NO" border="0" style="width:90%;height:500px;border:0px;"></iframe>';
            echo '</center>';

            print_footer(NULL, $course);
            exit;
        }
    } elseif($certrecord->certdate == 0) { ///Create certificate
        if(empty($action)) {
            certificate_view_header($course, $certificate, $cm);
            if ($certificate->delivery == 0)    {
                echo '<p align="center">'.get_string('openwindow', 'certificate').'</p>';
            } elseif ($certificate->delivery == 1)    {
                echo '<p align="center">'.get_string('opendownload', 'certificate').'</p>';
            } elseif ($certificate->delivery == 2)    {
                echo '<p align="center">'.get_string('openemail', 'certificate').'</p>';
            }

            $opt = new stdclass();
            $opt->id = $cm->id;
            $opt->action = 'get';
            echo '<center>';
            echo '<form action="" method="get" name="form1" target="certframe">';
            print_single_button('view.php', $opt, $strgetcertificate, 'get', '_blank');
            echo '</form>';
            echo '</center>';
            add_to_log($course->id, 'certificate', 'received', "view.php?id=$cm->id", $certificate->id, $cm->id);
            print_footer(NULL, $course);
            exit;
        }
        certificate_issue($course, $certificate, $certrecord, $cm); // update certrecord as issued
    }
// Output to pdf
    certificate_file_area($USER->id);
    $filesafe = clean_filename($certificate->name.'.pdf');
    $file = $CFG->dataroot.'/'.$course->id.'/moddata/certificate/'.$certificate->id.'/'.$USER->id.'/'.$filesafe;

    if ($certificate->savecert == 1){
        $pdf->Output($file, 'F');//save as file
    }
    if ($certificate->delivery == 0){
        $pdf->Output($filesafe, 'I');// open in browser
    } elseif ($certificate->delivery == 1 && $certrecord->certdate == 0){
        $pdf->Output($filesafe, 'D'); // force download when create
    } elseif ($certificate->delivery == 1 && $certrecord->certdate > 0){
        $pdf->Output($filesafe, 'I');// open in frame when review
    } elseif ($certificate->delivery == 2){
        certificate_email_students($USER, $course, $certificate, $certrecord);
        $pdf->Output($filesafe, 'I');// open in browser
        $pdf->Output('', 'S');// send
    }
?>