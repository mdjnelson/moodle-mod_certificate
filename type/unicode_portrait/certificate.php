<?php
include '../../lib/tcpdf/tcpdfprotection.php';
require_once('../../lib/tcpdf/config/lang/eng.php');
include '../../lib/tcpdf/tcpdf.php';

// Load certificate info
$certificateid = $certificate->id;
$certrecord = certificate_get_issue($course, $USER, $certificateid);
$strreviewcertificate = get_string('reviewcertificate', 'certificate');
$strgetcertificate = get_string('getcertificate', 'certificate');
$strgrade = get_string('grade', 'certificate');
$strcoursegrade = get_string('coursegrade', 'certificate');
$strcredithours = get_string('credithours', 'certificate');

// Date formatting - can be customized if necessary
setlocale (LC_TIME, '');
$certificatedate = '';
if ($certrecord->certdate > 0) {
$certdate = $certrecord->certdate;
}else $certdate = certificate_generate_date($certificate, $course);
if($certificate->printdate > 0)    {
    if ($certificate->datefmt == 1)    {
    $certificatedate = str_replace(' 0', '', strftime('%B %d, %Y', $certdate));
}   if ($certificate->datefmt == 2) {
    $certificatedate = date('F jS, Y', $certdate);
}   if ($certificate->datefmt == 3) {
    $certificatedate = str_replace(' 0', '', strftime(' %d %B %Y', $certdate));
}   if ($certificate->datefmt == 4) {
    $certificatedate = strftime('%B %Y', $certdate);
    }
}

//Grade formatting
$grade = '';
//Print the course grade
$coursegrade = certificate_get_course_grade($course->id);  
if ($certrecord->reportgrade == !null) {
$reportgrade = $certrecord->reportgrade;
    $grade = $strcoursegrade.':  '.$reportgrade;
}else   
    if($certificate->printgrade > 0) {
    if($certificate->printgrade == 1) {
    if($certificate->gradefmt == 1) {
    $grade = $strcoursegrade.':  '.$coursegrade->percentage;
}   if($certificate->gradefmt == 2) {
    $grade = $strcoursegrade.':  '.$coursegrade->points;
}   if($certificate->gradefmt == 3) {
    $grade = $strcoursegrade.':  '.$coursegrade->letter;

  }
} else {
//Print the mod grade
$modinfo = certificate_mod_grade($course, $certificate->printgrade);
if ($certrecord->reportgrade == !null) {
$modgrade = $certrecord->reportgrade;
    $grade = $modinfo->name.' '.$strgrade.': '.$modgrade;
}else 
    if($certificate->printgrade > 1) {
    if ($certificate->gradefmt == 1) {
    $grade = $modinfo->name.' '.$strgrade.': '.$modinfo->percentage;
}
    if ($certificate->gradefmt == 2) {          
    $grade = $modinfo->name.' '.$strgrade.': '.$modinfo->points;
}
    if($certificate->gradefmt == 3) {
    $grade = $modinfo->name.' '.$strgrade.': '.$modinfo->letter;
     }
	}
  }
}

// Print the code number
$code = '';
if($certificate->printnumber) {
$code = $certrecord->code;
}

//Print the student name
$studentname = '';
$studentname = $certrecord->studentname;
$classname = '';
$classname = $certrecord->classname;
//Print the credit hours
if($certificate->printhours) {
$credithours =  $strcredithours.': '.$certificate->printhours;
} else $credithours = '';

//Print the html text
$customtext = $certificate->customtext;

//Create new PDF document 

    $pdf = new TCPDF_Protection('P', 'pt', 'A4', true); 
    $pdf->SetProtection(array('print'));
    $pdf->print_header = false;
    $pdf->print_footer = false;
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setLanguageArray($l); //set language items
    $pdf->AddPage();
    	if(ini_get('magic_quotes_gpc')=='1')
		$customtext=stripslashes($customtext);
	
// Add images and lines
    $orientation = "P";
    print_border($certificate->borderstyle, $orientation);
	draw_frame($certificate->bordercolor, $orientation);
    print_watermark($certificate->printwmark, $orientation);
    print_seal($certificate->printseal, $orientation, 440, 590, '', '');
    print_signature($certificate->printsignature, $orientation, 85, 530, '', '');
	
// Add text
    $pdf->SetTextColor(0,0,128);
    cert_printtext(48, 170, 'C', 'FreeSerif', 'B', 26, get_string("titleportrait", "certificate"));
    $pdf->SetTextColor(0,0,0);
    cert_printtext(45, 230, 'C', 'FreeSerif', 'B', 20, get_string("introportrait", "certificate"));
    cert_printtext(45, 280, 'C', 'FreeSerif', '', 30, $studentname);
    cert_printtext(45, 330, 'C', 'FreeSerif', '', 20, get_string("statementportrait", "certificate"));
    cert_printtext(45, 380, 'C', 'FreeSerif', '', 20, $classname);
    cert_printtext(45, 420, 'C', 'FreeSerif', '', 20, get_string("ondayportrait", "certificate"));
    cert_printtext(45, 460, 'C', 'FreeSerif', '', 14, $certificatedate);
    cert_printtext(45, 540, 'C', 'FreeSerif', '', 10, $grade);
    cert_printtext(45, 552, 'C', 'FreeSerif', '', 10, $credithours);
    cert_printtext(45, 720, 'C', 'FreeSerif', '', 10, $code);
    $i = 0 ;
	if($certificate->printteacher){
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher')) {
		foreach ($teachers as $teacher) {
			$i++;
	cert_printtext(85, 590+($i *12) , 'L', 'FreeSerif', '', 12, fullname($teacher));
}}}
    cert_printtext(58, 600, '', '', '', '', '');
	$pdf->SetLeftMargin(85);
	$pdf->WriteHTML($customtext);
?>