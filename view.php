<?PHP // $Id$

// Created by Hugo Salgado, July, 2004
// Updated by David T. Cannon, July, 2005
// Updated by Chardelle Busch, June, 2006 

/// This page prints a particular instance of certificate
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);

require_once("../../config.php");
require_once("lib.php");
include '../../lib/fpdf/fpdf.php';
include '../../lib/fpdf/fpdf_protection.php';

 $id = required_param('id', PARAM_INT);    // Course Module ID
    $action = optional_param('action', '');
if ($id) {
	if (! $cm = get_record("course_modules", "id", $id)) {
		error("course Module ID was incorrect");
	}

	if (! $course = get_record("course", "id", $cm->course)) {
		error("course is misconfigured");
	}

	if (! $cert = get_record("certificate", "id", $cm->instance)) {
		error("course module is incorrect");
	}
}
else {
	if (! $cert = get_record("certificate", "id", $a)) {
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

	/// Get all required strings
	$strcertificates = get_string("modulenameplural", "certificate");
	$strcertificate  = get_string("modulename", "certificate");

// log update
add_to_log($course->id, "certificate", "view", "view.php?id=$cm->id", $cert->id, $cm->id);

// check if viewed before, if not create record
$certrecord = array();
$certrecord = check_cert_exists($course, $USER);
$studentname = $certrecord->studentname ;

/// Print the page header
if ($course->category) {
	$navigation = "<A HREF=\"../../course/view.php?id=$course->id\">$course->shortname</A> ->";
}

$strcertificates = get_string("modulenameplural", "certificate");
$strcertificate  = get_string("modulename", "certificate");

//
// Load certificate info
//

$type = $cert->type;

// Date
switch($cert->date_fmt) {
	case 0:
	$date_fmt = '';
	break;
	case 1:
	$date_fmt = 'F j, Y';
	break;
	case 2:
	$date_fmt = 'F jS, Y';
	break;
	case 3:
	$date_fmt = 'j F Y';
	break;
	case 4:
	$date_fmt = 'F Y';
	break;
}

// One of these 2 lines seem to work, depending on system
$completion_date = date("$date_fmt", $certrecord->cert_date);
//$completion_date = date("$date_fmt", strtotime($certrecord->cert_date));

$color = $cert->border_color;

// Get teacher name of group
if (groupmode($course, null) == SEPARATEGROUPS) {   // Separate groups are being used
	if (!$group = user_group($course->id, $USER->id)) {             // Try to find a group
		$group->id = 0;                                             // Not in a group, never mind
	}
	$teachers = get_group_teachers_noedit($course->id, $group->id);        // Works even if not in group
}
else {
	$teachers = get_course_teachers($course->id);
}

$grade = '';
if($cert->print_grade > 0) {
	if($cert->print_grade == 1) {
		//echo $cert->print_grade;
		//$grade = 'Grade: '.cert_get_grade($course, $cert->print_grade);
		$grade = 'Grade: '.get_course_grade( $course->id);
	} else {
//		$grade = 'Grade: '.cert_get_grade($course, $cert->print_grade);
		$modinfo = cert_get_grade($course, $cert->print_grade);
	$grade = 'Grade for '.$modinfo->name.': '.$modinfo->grade;
	}
	if((!$grade) || isteacher($course->id)) {
		$grade = "Grade here";
	}
}

// done loading cert info

// May create a routine that teachers can do stuff here
/* if (isteacher($course->id)) {
print_header("$course->shortname: $certificate->name", "$course->fullname",
"$navigation <A HREF=index.php?id=$course->id>$strcertificates</A> -> $certificate->name",
"", "", true, update_module_button($cm->id, $course->id, $strcertificate),
navmenu($course, $cm));

echo get_string("advertencia", "certificate");

print_footer($course);

}
*/

//
//  Determine certificate type
//
switch($type) {
	case 0:
	$orientation = "L";
	$pdf = new FPDF_Protection($orientation, 'pt', 'A4');
	$pdf->SetProtection(array('print'));
	$pdf->AddPage();
	// Add images
	print_border($cert->border_style, $color, $orientation);
	print_watermark($cert->print_wmark, $orientation);
	print_seal($cert->print_seal, $orientation);
	print_teachers($teachers);
	print_signature($cert->print_signature, $orientation);
	// Add text
	cert_printtext(170, 125, 'C', 'Helvetica', 'B', 30, get_string("title".$type, "certificate"));
	cert_printtext(170, 180, 'C', 'Helvetica', 'B', 20, get_string("intro".$type, "certificate"));
	cert_printtext(170, 230, 'C', 'Times', '', 30, $certrecord->studentname);
	cert_printtext(170, 280, 'C', 'Helvetica', '', 20, get_string("statement".$type, "certificate"));
	cert_printtext(170, 330, 'C', 'Times', '', 20, $course->fullname);
	cert_printtext(170, 380, 'C', 'Helvetica', '', 14, $completion_date);
	cert_printtext(170, 420, 'C', 'Helvetica', '', 10, $grade);
	print_code($certrecord->code);
	break;
	case 1:
	$orientation = "L";
	$pdf = new FPDF_Protection($orientation, 'pt', 'A4');
	$pdf->SetProtection(array('print'));
	$pdf->AddPage();
	// Add images
	print_border($cert->border_style, $color, $orientation);
	print_watermark($cert->print_wmark, $orientation);
	print_seal($cert->print_seal, $orientation);
	print_teachers($teachers);
	print_signature($cert->print_signature, $orientation);
	// Add text
	cert_printtext(45, 125, 'C', 'Helvetica', 'B', 30, get_string("title".$type, "certificate"));
	cert_printtext(45, 180, 'C', 'Helvetica', 'B', 20, get_string("intro".$type, "certificate"));
	cert_printtext(45, 230, 'C', 'Times', '', 30, $certrecord->studentname);
	cert_printtext(45, 280, 'C', 'Helvetica', '', 20, get_string("statement".$type, "certificate"));
	cert_printtext(45, 330, 'C', 'Times', '', 20, $course->fullname);
	cert_printtext(45, 380, 'C', 'Helvetica', '', 14, $completion_date);
	cert_printtext(45, 420, 'C', 'Helvetica', '', 10, $grade);
	print_code($certrecord->code);
	break;
	case 2:
	$orientation = "L";
	$pdf = new FPDF_Protection($orientation, 'pt', 'A4');
	$pdf->SetProtection(array('print'));
	$pdf->AddPage();
	// Add images
	print_border($cert->border_style, $color, $orientation);
	print_watermark($cert->print_wmark, $orientation);
	print_seal($cert->print_seal, $orientation);
	print_teachers($teachers);
	print_signature($cert->print_signature, $orientation);
	// Add text
	cert_printtext(170, 125, 'C', 'Helvetica', 'B', 30, get_string("title".$type, "certificate"));
	cert_printtext(170, 180, 'C', 'Helvetica', 'B', 20, get_string("intro".$type, "certificate"));
	cert_printtext(170, 230, 'C', 'Times', '', 30, $certrecord->studentname);
	cert_printtext(170, 280, 'C', 'Helvetica', '', 20, get_string("statement".$type, "certificate"));
	cert_printtext(170, 330, 'C', 'Times', '', 20, $course->fullname);
	cert_printtext(170, 380, 'C', 'Helvetica', '', 14, $completion_date);
	cert_printtext(170, 420, 'C', 'Helvetica', '', 10, $grade);
	print_code($certrecord->code);
	break;
	case 3:
	$orientation = "P";
    $pdf = new FPDF_Protection($orientation, 'pt', 'A4');
    $pdf->SetProtection(array('print'));
	$pdf->AddPage();
    // Add images
    print_border($cert->border_style, $color, $orientation);
    print_watermark($cert->print_wmark, $orientation);
    print_seal($cert->print_seal, $orientation);
    print_teachers($teachers);
    print_signature($cert->print_signature, $orientation);
    // Add text
    cert_printtext(45, 200, 'C', 'Arial', 'B', 30, get_string("title".$type, "certificate"));
    cert_printtext(45, 300, 'C', 'Arial', 'B', 20, get_string("intro".$type, "certificate"));
    cert_printtext(45, 350, 'C', 'Arial', '', 30, $certrecord->studentname);
    cert_printtext(45, 400, 'C', 'Arial', '', 20, get_string("hours".$type, "certificate"));
    cert_printtext(45, 430, 'C', 'Arial', '', 20, get_string("statement".$type, "certificate"));
    cert_printtext(45, 520, 'C', 'Arial', '', 30, $course->fullname);
    cert_printtext(45, 590, 'C', 'Arial', '', 20, get_string("onday".$type, "certificate"));
    cert_printtext(45, 650, 'C', 'Arial', '', 20, $completion_date);
    cert_printtext(45, 750, 'C', 'Arial', '', 10, $grade);
    print_code($certrecord->code);
    break;
}	
$pdf->Output('certificate.pdf', 'I');
?>
