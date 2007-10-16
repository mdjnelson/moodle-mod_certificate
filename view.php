<?PHP // $Id: version.php,v 3.1.0

require_once('../../config.php');
require_once('lib.php');
include '../../lib/fpdf/fpdf.php';
include '../../lib/fpdf/fpdfprotection.php';
include_once('html2pdf.php');

    $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '');
    if ($id) {
        if (! $cm = get_coursemodule_from_id('certificate', $id)) {
                error("Course Module ID was incorrect");
            }
       
        if (! $course = get_record("course", "id", $cm->course)) {
            error("course is misconfigured");
        }
    
        if (! $certificate = get_record("certificate", "id", $cm->instance)) {
            error("course module is incorrect");
        }
    } else {
        if (! $certificate = get_record("certificate", "id", $a)) {
            error("course module is incorrect");
        }
        if (! $course = get_record("course", "id", $certificate->course)) {
            error("course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("certificate", $certificate->id, $course->id)) {
            error("course Module ID was incorrect");
        }
    }

    require_login($course->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/certificate:view', $context);

// log update
    add_to_log($course->id, "certificate", "view", "view.php?id=$cm->id", $certificate->id, $cm->id);

// Get teacher name of group
    if (groupmode($course, null) == SEPARATEGROUPS) {   // Separate groups are being used
        if (!$group = user_group($course->id, $USER->id)) {             // Try to find a group
            $group->id = 0;                                             // Not in a group, never mind
        }
        $teachers = get_group_teachers($course->id, $group->id);        // Works even if not in group
    } else {
        $teachers = get_course_teachers($course->id);
    }

/// Create certrecord
    certificate_prepare_issue($course, $USER);

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
  
// Load custom type
    $type = $certificate->certificatetype;
    require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");
    $certificateid = $certificate->id;
    $certrecord = certificate_get_issue($course, $USER, $certificateid);

//Review certificate
    if($certrecord->certdate > 0) {
        if (!isset($_GET['certificate'])) {    
            view_header($course, $certificate, $cm);
    
            echo '<p align="center">'.get_string('viewed', 'certificate').'<br />'.userdate($certrecord->certdate).'</p>';
            echo '<center>';
            echo '<form action="'.$CFG->wwwroot.'/mod/certificate/view.php" method="get" name="form1" target="certframe">';
            echo '<input type="hidden" name="id" value="'.$cm->id.'" />';
            echo '<input type="hidden" name="certificate" value="'.$certificate->id.'" />';
            echo '<input type="submit" name="Submit" value="'.$strreviewcertificate.'" />';
            echo '</form>';
            echo '<iframe name="certframe" id="certframe" frameborder="NO" border="0" style="width:90%;height:500px;border:0px;"></iframe>';
            echo '</center>';

            print_footer(NULL, $course);
            exit;
        }
    }

//Create certificate 
    if($certrecord->certdate == 0) {
        if(!isset($_GET['certificate'])) {    
            view_header($course, $certificate, $cm);
            if ($certificate->delivery == 0)    {
                echo '<p align="center">'.get_string('openwindow', 'certificate').'</p>';
            }
            if ($certificate->delivery == 1)    {
                echo '<p align="center">'.get_string('opendownload', 'certificate').'</p>';
            }
            if ($certificate->delivery == 2)    {
                echo '<p align="center">'.get_string('openemail', 'certificate').'</p>';
            }

            echo '<center>';
            echo '<form action="'.$CFG->wwwroot.'/mod/certificate/view.php" method="get" name="form1" target="_blank">';
            echo '<input type="hidden" name="id" value="'.$cm->id.'" />';
            echo '<input type="hidden" name="certificate" value="'.$certificate->id.'" />';
            echo '<input type="submit" name="Submit" value="'.$strgetcertificate.'" />';
            echo '</form>';
            echo '</center>';
            add_to_log($course->id, "certificate", "received", "view.php?id=$cm->id", $certificate->id, $cm->id);
            print_footer(NULL, $course);
            exit;
        }
        certificate_issue($course, $USER); // update certrecord as issued
    }
// Output to pdf
    $userid = $USER->id;
    certificate_file_area($userid);
    $file = $CFG->dataroot.'/'.$course->id.'/moddata/certificate/'.$certificate->id.'/'.$USER->id.'/'.$certificate->name.'.pdf';

    if ($certificate->savecert == 1){
        $pdf->Output($file, 'F');//save as file
    }
    if ($certificate->delivery == 0){
        $pdf->Output(''.$certificate->name.'.pdf', 'I');// open in browser
    }
    if ($certificate->delivery == 1 && $certrecord->certdate == 0){
        $pdf->Output(''.$certificate->name.'.pdf', 'D'); // force download when create
    }
	if ($certificate->delivery == 1 && $certrecord->certdate > 0){
        $pdf->Output(''.$certificate->name.'.pdf', 'I');// open in frame when review
    }
    if ($certificate->delivery == 2){
        certificate_email_students($USER);
        $pdf->Output(''.$certificate->name.'.pdf', 'I');// open in browser
        $pdf->Output('', 'S');// send
    }
?>