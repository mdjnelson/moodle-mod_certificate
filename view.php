<?PHP // $Id: version.php,v 3.1.0

require_once("../../config.php");
require_once("lib.php");
include '../../lib/fpdf/fpdf.php';
include '../../lib/fpdf/fpdfprotection.php';

 $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '');
  if ($id) {
    if (! $cm = get_record("course_modules", "id", $id)) {
        error("course Module ID was incorrect");
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
// log update
add_to_log($course->id, "certificate", "view", "view.php?id=$cm->id", $certificate->id, $cm->id);
// Get teacher name of group
if (groupmode($course, null) == SEPARATEGROUPS) {   // Separate groups are being used
    if (!$group = user_group($course->id, $USER->id)) {             // Try to find a group
        $group->id = 0;                                             // Not in a group, never mind
    }
    $teachers = get_group_teachers($course->id, $group->id);        // Works even if not in group
}
else {
    $teachers = get_course_teachers($course->id);
}
 
// Load custom type
$type = $certificate->certificatetype;
require ("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");
$certificateid = $certificate->id;
$certrecord = certificate_get_issue($course, $USER, $certificateid);

//Review certificate
if($certrecord) {
if (!isset($_GET['certificate'])) {    
view_header($course, $certificate, $cm);

    echo "<p align=\"center\">".get_string('viewed', 'certificate')."<br> ".userdate($certrecord->timecreated)."</p>";
    echo '<center>';
    echo '<form action="" method="get" name="form1" target="certframe">';
    echo '<input type="hidden" name="id" value='.$cm->id.'>';
    echo '<input type="hidden" name="certificate" value='.$certificate->id.' >';
    echo '<input type="submit" name="Submit" value="'.$strreviewcertificate.'" />';
    echo '</form>';
    echo '<iframe name="certframe" id="certframe" frameborder="NO" border="0" style="width:90%;height:500px;border:0px;"></iframe>';
    echo '</center>';

print_footer(NULL, $course);
    exit;
}
} 
//Create certificate 
if(!$certrecord) {
if(!isset($_GET['certificate'])) {    
view_header($course, $certificate, $cm);
    if ($certificate->delivery == 0)    {
    echo "<p align=\"center\">".get_string('openwindow', 'certificate')."<br> </p>";
}   if ($certificate->delivery == 1)    {
    echo "<p align=\"center\">".get_string('opendownload', 'certificate')."<br> </p>";
}   if ($certificate->delivery == 2)    {
    echo "<p align=\"center\">".get_string('openemail', 'certificate')."<br> </p>";
}

    echo '<center>';
    echo '<form action="'.certificate_prepare_issue($course, $USER).'" method="get" name="form1" target="_blank">';
    echo '<input type="hidden" name="id" value='.$cm->id.'>';
    echo '<input type="hidden" name="certificate" value='.$certificate->id.' >';
    echo '<input type="submit" name="Submit" value="'.$strgetcertificate.'">';
    echo '</form>';
    echo '</center>';
add_to_log($course->id, "certificate", "received", "view.php?id=$cm->id", $certificate->id, $cm->id);
print_footer(NULL, $course);
    exit;
}
}
// Output to pdf
$userid = $USER->id;
certificate_file_area($userid);
$file = $CFG->dataroot.'/'.$course->id.'/moddata/certificate/'.$certificate->id.'/'.$USER->id.'/certificate.pdf';

if($certificate->save == 1){
$pdf->Output($file, 'F');//save as file
}
if($certificate->delivery == 0){
$pdf->Output('certificate.pdf', 'I');// open in browser
}
if($certificate->delivery == 1){
$pdf->Output('certificate.pdf', 'D'); // force download
}
if($certificate->delivery == 2){
$pdf->Output('certificate.pdf', 'I');// open in browser
$pdf->Output('', 'S');// send
certificate_email_students($USER);
}
?>