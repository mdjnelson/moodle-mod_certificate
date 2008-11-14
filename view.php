<?PHP // $Id: version.php,v 3.1.0

require_once('../../config.php');
require_once('lib.php');
include '../../lib/fpdf/fpdf.php';
include '../../lib/fpdf/fpdfprotection.php';
include_once('html2pdf.php');

    $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '', PARAM_ALPHA);

    if (! $cm = get_coursemodule_from_id('certificate', $id)) {
        error('Course Module ID was incorrect');
    }
    if (! $course = get_record('course', 'id', $cm->course)) {
        error('course is misconfigured');
    }
    if (! $certificate = get_record('certificate', 'id', $cm->instance)) {
        error('course module is incorrect');
    }

    global $USER;
    require_login($course->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:view', $context);

// log update
    add_to_log($course->id, 'certificate', 'view', "view.php?id=$cm->id", $certificate->id, $cm->id);

    //check to see if requiredcertification and user has not completed.
    if (!empty($certificate->requiredcertification)) {
        //get any certifications this user has.       
        $usercert = get_field('user_info_data', 'data','fieldid', $certificate->requiredcertification, 'userid', $USER->id);        
        if (empty($usercert)) {
            view_header($course, $certificate, $cm);
            print_simple_box(notify(get_string('requiredcertificationdesc','local')));
            print_continue("$CFG->wwwroot/course/view.php?id=$course->id");
            print_footer();
            die;
        }
    }

/// Check locked grades
    $restrict_errors = certificate_grade_condition();

/// Display errors and die
    if (!empty($restrict_errors) && !has_capability('mod/certificate:manage', $context)) {
        $errortext = '';
        view_header($course, $certificate, $cm);
        foreach($restrict_errors as $err) {
            $errortext .= '<p><center>' . $err . '</center></p>';
        }
        print_simple_box($errortext);
		print_continue("$CFG->wwwroot/course/view.php?id=$course->id");
        print_footer();
        die;
    }

/// Create certrecord
    certificate_prepare_issue($course, $USER, $certificate);

/// Load custom type
    $type = $certificate->certificatetype;
    $certificateid = $certificate->id;
    $certrecord = get_record('certificate_issues', 'certificateid', $certificateid, 'userid', $USER->id);

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
            view_header($course, $certificate, $cm);

            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
            echo '<center>';
            $opt = new stdclass();
            $opt->id = $cm->id;
            $opt->action = 'review';
            print_single_button('', $opt, $strreviewcertificate, 'get', 'certframe');
            //TODO: not sure why this iframe is used - in FF it seems to operate the same as the create certificate below. - might be able to be removed?
            echo '<iframe name="certframe" id="certframe" frameborder="NO" border="0" style="width:90%;height:500px;border:0px;"></iframe>';
            echo '</center>';

            print_footer(NULL, $course);
            exit;
        }
    } elseif($certrecord->certdate == 0) { ///Create certificate
        if(empty($action)) {
            view_header($course, $certificate, $cm);
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
            print_single_button('view.php', $opt, $strgetcertificate, 'get', '_blank');
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