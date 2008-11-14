<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from view.php in mod/tracker
}
// Date formatting - can be customized if necessary
$certificatedate = '';
if ($certrecord->certdate > 0) {
    $certdate = $certrecord->certdate;
}else {
    $certdate = certificate_generate_date($certificate, $course);
}
if($certificate->printdate > 0)    {
    if ($certificate->datefmt == 1)    {
        $certificatedate = str_replace(' 0', ' ', strftime('%B %d, %Y', $certdate));
    }
    if ($certificate->datefmt == 2) {
        $certificatedate = date('F jS, Y', $certdate);
    }
    if ($certificate->datefmt == 3) {
        $certificatedate = str_replace(' 0', '', strftime('%d %B %Y', $certdate));
}
   if ($certificate->datefmt == 4) {
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
//Print the credit hours
if($certificate->printhours) {
$credithours =  $strcredithours.': '.$certificate->printhours;
} else $credithours = '';

//Print the html text
$customtext = $certificate->customtext;

// Add pdf page
    $orientation = "P";
    $pdf=new PDF($orientation, 'pt', 'Letter');
    $pdf->SetProtection(array('print'));
    $pdf->AddPage();
    if(ini_get('magic_quotes_gpc')=='1')
		$customtext=stripslashes($customtext);

// Add images and lines
    print_border_letter($certificate->borderstyle, $orientation);
	draw_frame_letter($certificate->bordercolor, $orientation);
    print_watermark_letter($certificate->printwmark, $orientation);
    print_seal($certificate->printseal, $orientation, 440, 590, '', '');
    print_signature($certificate->printsignature, $orientation, 98, 530, '', '');

// Add text
    $pdf->SetTextColor(0,0,128);
    cert_printtext(58, 170, 'C', 'Helvetica', 'B', 28, utf8_decode(get_string("titleportrait", "certificate")));
    $pdf->SetTextColor(0,0,0);
    cert_printtext(58, 230, 'C', 'Times', 'B', 20, utf8_decode(get_string("introportrait", "certificate")));
    cert_printtext(58, 280, 'C', 'Helvetica', '', 30, utf8_decode($studentname));
    cert_printtext(58, 330, 'C', 'Helvetica', '', 20, utf8_decode(get_string("statementportrait", "certificate")));
    cert_printtext(58, 380, 'C', 'Helvetica', '', 20, utf8_decode($course->fullname));
    cert_printtext(58, 420, 'C', 'Helvetica', '', 20, utf8_decode(get_string("ondayportrait", "certificate")));
    cert_printtext(58, 460, 'C', 'Helvetica', '', 14, utf8_decode($certificatedate));
    cert_printtext(58, 540, 'C', 'Times', '', 10, utf8_decode($grade));
    cert_printtext(58, 551, 'C', 'Times', '', 10, utf8_decode($outcome));
    cert_printtext(58, 562, 'C', 'Times', '', 10, utf8_decode($credithours));
    cert_printtext(58, 720, 'C', 'Times', '', 10, utf8_decode($code));
    $i = 0 ;
	if($certificate->printteacher){
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher')) {
		foreach ($teachers as $teacher) {
			$i++;
	cert_printtext(98, 590+($i *12) , 'L', 'Times', '', 12, utf8_decode(fullname($teacher)));
}}}
    cert_printtext(58, 600, '', '', '', '', '');
	$pdf->SetLeftMargin(98);
	$pdf->WriteHTML($customtext);
?>