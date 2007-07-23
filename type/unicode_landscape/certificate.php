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

//Grade formatting - can be customized if necessary
$grade = '';
//Print the course grade
$coursegrade = certificate_get_course_grade($course->id);    
    if($certificate->printgrade > 0) {
    if($certificate->printgrade == 1) {
    if($certificate->gradefmt == 1) {
    $grade = $strcoursegrade.':  '.$coursegrade->percentage.'%';
}   if($certificate->gradefmt == 2) {
    $grade = $strcoursegrade.':  '.$coursegrade->points;
}   if($certificate->gradefmt == 3) {
    $clg = $coursegrade->percentage;
    if ($clg <= 100.99){
    $grade = $strcoursegrade.':  '.'A';
}   if ($clg <= 92.99){
    $grade = $strcoursegrade.':  '.'A-'; 
}   if ($clg <=89.99){
    $grade = $strcoursegrade.':  '.'B+';
}   if ($clg <= 82.99){
    $grade = $strcoursegrade.':  '.'B-'; 
}   if ($clg <= 86.99){
    $grade = $strcoursegrade.':  '.'B';
}   if ($clg <= 79.99){
    $grade = $strcoursegrade.':  '.'C+'; 
}   if ($clg <= 76.99){
    $grade = $strcoursegrade.':  '.'C'; 
}   if ($clg <= 72.99){
    $grade = $strcoursegrade.':  '.'C-'; 
}   if ($clg <= 69.99){
    $grade = $strcoursegrade.':  '.'D+'; 
}   if ($clg <= 66.99){
    $grade = $strcoursegrade.':  '.'D'; 
}   if ($clg <= 59.99){
    $grade = $strcoursegrade.':  '.'F';
    }
  }
} else {
//Print the mod grade
$modinfo = certificate_mod_grade($course, $certificate->printgrade);
    if($certificate->printgrade > 1) {
    if ($certificate->gradefmt == 1) {
    $grade = $modinfo->name.' '.$strgrade.': '.$modinfo->percentage.'%';
}
    if ($certificate->gradefmt == 2) {          
    $grade = $modinfo->name.' '.$strgrade.': '.$modinfo->points;
}
    if($certificate->gradefmt == 3) {
    $mlg = $modinfo->percentage;
    if ($mlg <= 100.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'A';
}   if ($mlg <= 92.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'A-'; 
}   if ($mlg <=89.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'B+';
}   if ($mlg <= 82.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'B-'; 
}   if ($mlg <= 86.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'B';
}   if ($mlg <= 79.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'C+'; 
}   if ($mlg <= 76.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'C'; 
}   if ($mlg <= 72.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'C-'; 
}   if ($mlg <= 69.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'D+'; 
}   if ($mlg <= 66.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'D'; 
}   if ($mlg <= 59.99){
    $grade = $modinfo->name.' '.$strgrade.': '.'F';
    }
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

    $pdf = new TCPDF_Protection('L', 'pt', 'A4', true); 
    $pdf->SetProtection(array('print'));
    $pdf->print_header = false;
    $pdf->print_footer = false;
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setLanguageArray($l); //set language items
    $pdf->AddPage();
    	if(ini_get('magic_quotes_gpc')=='1')
		$customtext=stripslashes($customtext);
	
// Add images and lines
    $orientation = "L";
    print_border($certificate->borderstyle, $orientation);
	draw_frame($certificate->bordercolor, $orientation);
    print_watermark($certificate->printwmark, $orientation);
    print_seal($certificate->printseal, $orientation, 590, 425, '', '');
    print_signature($certificate->printsignature, $orientation, 130, 430, '', '');
	
// Add text
    $pdf->SetTextColor(0,0,120);
    cert_printtext(170, 125, 'C', 'FreeSerif', 'B', 30, get_string('titlelandscape', 'certificate'));
    $pdf->SetTextColor(0,0,0);
    cert_printtext(170, 180, 'C', 'FreeSerif', '', 20, get_string('introlandscape', 'certificate'));
    cert_printtext(170, 230, 'C', 'FreeSerif', 'I', 30, $studentname);
	cert_printtext(170, 280, 'C', 'FreeSerif', '', 20, get_string('statementlandscape', 'certificate'));
    cert_printtext(170, 330, 'C', 'FreeSerif', '', 20, $classname);
    cert_printtext(170, 380, 'C', 'FreeSerif', '', 14, $certificatedate);
    cert_printtext(170, 420, 'C', 'FreeSerif', '', 10, $grade);
    cert_printtext(170, 432, 'C', 'FreeSerif', '', 10, $credithours);
    cert_printtext(170, 500, 'C', 'FreeSerif', '', 10, $code);
    $i = 0 ;
	if($certificate->printteacher){
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort='u.lastname ASC')) {
		foreach ($teachers as $teacher) {
			$i++;
	cert_printtext(130, 440+($i *12) , 'L', 'FreeSerif', '', 12, fullname($teacher));
}}}


    cert_printtext(150, 450, '', '', '', '', '');
	$pdf->SetLeftMargin(130);
	$pdf->WriteHTML($customtext);
?>