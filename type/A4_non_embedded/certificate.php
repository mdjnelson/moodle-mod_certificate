<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from view.php in mod/tracker
}

// Date formatting - can be customized if necessary
$certificatedate = '';
if ($certrecord->certdate > 0) {
$certdate = $certrecord->certdate;
}else $certdate = certificate_generate_date($certificate, $course);
if($certificate->printdate > 0)    {
    if ($certificate->datefmt == 1)    {
    $certificatedate = str_replace(' 0', ' ', strftime('%B %d, %Y', $certdate));
}   if ($certificate->datefmt == 2) {
    $certificatedate = date('F jS, Y', $certdate);
}   if ($certificate->datefmt == 3) {
    $certificatedate = str_replace(' 0', '', strftime('%d %B %Y', $certdate));
}   if ($certificate->datefmt == 4) {
    $certificatedate = strftime('%B %Y', $certdate);
}   if ($certificate->datefmt == 5) {
    $timeformat = get_string('strftimedate');
    $certificatedate = userdate($certdate, $timeformat);
    }
}

//Grade formatting
$grade = '';
//Print the course grade
$coursegrade = certificate_print_course_grade($course);
if ($certificate->printgrade == 1 && $certrecord->reportgrade == !null) {
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
$modinfo = certificate_print_mod_grade($course, $certificate->printgrade);
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
//Print the outcome
$outcome = '';
$outcomeinfo = certificate_print_outcome($course, $certificate->printoutcome);
if($certificate->printoutcome > 0) {
    $outcome = $outcomeinfo->name.': '.$outcomeinfo->grade;
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

    $customtext = $certificate->customtext;
    $orientation = $certificate->orientation;
    $pdf = new TCPDF($orientation, 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetProtection(array('print'));
    $pdf->SetTitle($certificate->name);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

//Define variables

//Landscape
	if ($orientation == 'L') {
	$x = 10;
	$y = 30;
	$sealx = 230;
	$sealy = 150;
	$sigx = 47;
	$sigy = 155;
	$custx = 47;
	$custy = 155;
	$wmarkx = 40;
	$wmarky = 31;
	$wmarkw = 212;
	$wmarkh = 148;
	$brdrx = 0;
	$brdry = 0;
	$brdrw = 297;
	$brdrh = 210;
    $codey = 175;
	} else {
//Portrait
	$x = 10;
	$y = 40;
	$sealx = 150;
	$sealy = 220;
	$sigx = 30;
	$sigy = 230;
	$custx = 30;
	$custy = 230;
	$wmarkx = 26;
	$wmarky = 58;
	$wmarkw = 158;
	$wmarkh = 170;
	$brdrx = 0;
	$brdry = 0;
	$brdrw = 210;
	$brdrh = 297;
    $codey = 250;
	}

// Add images and lines
    print_border($certificate->borderstyle, $orientation, $brdrx, $brdry, $brdrw, $brdrh);
    draw_frame($certificate->bordercolor, $orientation);
// Set alpha to semi-transparency
    $pdf->SetAlpha(0.2);
    print_watermark($certificate->printwmark, $orientation, $wmarkx, $wmarky, $wmarkw, $wmarkh);
    $pdf->SetAlpha(1);
    print_seal($certificate->printseal, $orientation, $sealx, $sealy, '', '');
    print_signature($certificate->printsignature, $orientation, $sigx, $sigy, '', '');
    
// Add text
    $pdf->SetTextColor(0,0,120);
    cert_printtext($x, $y, 'C', 'Helvetica', '', 30, get_string('title', 'certificate'));
    $pdf->SetTextColor(0,0,0);
    cert_printtext($x, $y+20, 'C', 'Times', '', 20, get_string('certify', 'certificate'));
    cert_printtext($x, $y+36, 'C', 'Helvetica', '', 30, $studentname);
    cert_printtext($x, $y+55, 'C', 'Helvetica', '', 20, get_string('statement', 'certificate'));
    cert_printtext($x, $y+72, 'C', 'Helvetica', '', 20, $classname);
    cert_printtext($x, $y+92, 'C', 'Helvetica', '', 14, $certificatedate);
    cert_printtext($x, $y+102, 'C', 'Times', '', 10, $grade);
    cert_printtext($x, $y+112, 'C', 'Times', '', 10, $outcome);
    cert_printtext($x, $y+122, 'C', 'Times', '', 10, $credithours);
    cert_printtext($x, $codey, 'C', 'Times', '', 10, $code);
    $i = 0 ;
    if($certificate->printteacher){
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort='u.lastname ASC','','','','',false)) {
        foreach ($teachers as $teacher) {
            $i++;
    cert_printtext($sigx, $sigy+($i *4) , 'L', 'Times', '', 12, fullname($teacher));
}}}

    cert_printtext($custx, $custy, 'L', '', '', '', $customtext);
?>