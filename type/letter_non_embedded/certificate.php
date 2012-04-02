<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from view.php
}

// Date formatting - can be customized if necessary
$certificatedate = '';
if ($certrecord->certdate > 0) {
    $certdate = $certrecord->certdate;
} else {
    $certdate = certificate_generate_date($certificate, $course);
}
if ($certificate->printdate > 0) {
    if ($certificate->datefmt == 1) {
        $certificatedate = str_replace(' 0', ' ', strftime('%B %d, %Y', $certdate));
    } else if ($certificate->datefmt == 2) {
        $certificatedate = date('F jS, Y', $certdate);
    } else if ($certificate->datefmt == 3) {
        $certificatedate = str_replace(' 0', '', strftime('%d %B %Y', $certdate));
    } else if ($certificate->datefmt == 4) {
        $certificatedate = strftime('%B %Y', $certdate);
    } else if ($certificate->datefmt == 5) {
        $certificatedate = userdate($certdate, get_string('strftimedate', 'langconfig'));
    }
}

// Grade formatting
$grade = '';
// Print the course grade
$coursegrade = certificate_print_course_grade($course);
if ($certificate->printgrade == 1 && $certrecord->reportgrade) {
    $reportgrade = $certrecord->reportgrade;
    $grade = $strcoursegrade . ':  ' . $reportgrade;
} else if ($certificate->printgrade > 0) {
    if ($certificate->printgrade == 1) {
        if ($certificate->gradefmt == 1) {
            $grade = $strcoursegrade . ':  ' . $coursegrade->percentage;
        } else if ($certificate->gradefmt == 2) {
            $grade = $strcoursegrade . ':  ' . $coursegrade->points;
        } else if ($certificate->gradefmt == 3) {
            $grade = $strcoursegrade . ':  ' . $coursegrade->letter;
        }
    } else { // Print the mod grade
        $modinfo = certificate_print_mod_grade($course, $certificate->printgrade);
        if ($certrecord->reportgrade) {
            $modgrade = $certrecord->reportgrade;
            $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modgrade;
        } else if ($certificate->printgrade > 1) {
            if ($certificate->gradefmt == 1) {
                $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->percentage;
            } else if ($certificate->gradefmt == 2) {
                $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->points;
            } else if ($certificate->gradefmt == 3) {
                $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->letter;
            }
        }
    }
}
// Print the outcome
$outcome = '';
$outcomeinfo = certificate_print_outcome($course, $certificate->printoutcome);
if ($certificate->printoutcome > 0) {
    $outcome = $outcomeinfo->name . ': ' . $outcomeinfo->grade;
}

// Print the code number
$code = '';
if ($certificate->printnumber) {
    $code = $certrecord->code;
}

// Print the student name
$studentname = '';
$studentname = $certrecord->studentname;
$classname = '';
$classname = $certrecord->classname;
// Print the credit hours
if ($certificate->printhours) {
    $credithours = $strcredithours . ': ' . $certificate->printhours;
} else {
    $credithours = '';
}

$pdf = new TCPDF($certificate->orientation, 'pt', 'Letter', true, 'UTF-8', false);
// $pdf->SetProtection(array('print'));
$pdf->SetTitle($certificate->name);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

// Landscape
if ($certificate->orientation == 'L') {
    $x = 28;
    $y = 125;
    $sealx = 590;
    $sealy = 425;
    $sigx = 130;
    $sigy = 440;
    $custx = 133;
    $custy = 440;
    $wmarkx = 100;
    $wmarky = 90;
    $wmarkw = 600;
    $wmarkh = 420;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 792;
    $brdrh = 612;
    $codey = 505;
} else { // Portrait
    $x = 28;
    $y = 170;
    $sealx = 440;
    $sealy = 590;
    $sigx = 85;
    $sigy = 580;
    $custx = 88;
    $custy = 580;
    $wmarkx = 78;
    $wmarky = 130;
    $wmarkw = 450;
    $wmarkh = 480;
    $brdrx = 10;
    $brdry = 10;
    $brdrw = 594;
    $brdrh = 771;
    $codey = 660;
}

// Add images and lines
print_border($pdf, $certificate, $brdrx, $brdry, $brdrw, $brdrh);
draw_frame_letter($pdf, $certificate);
// Set alpha to semi-transparency
$pdf->SetAlpha(0.1);
print_watermark($pdf, $certificate, $wmarkx, $wmarky, $wmarkw, $wmarkh);
$pdf->SetAlpha(1);
print_seal($pdf, $certificate, $sealx, $sealy, '', '');
print_signature($pdf, $certificate, $sigx, $sigy, '', '');

// Add text
$pdf->SetTextColor(0, 0, 120);
cert_printtext($pdf, $x, $y, 'C', 'Helvetica', '', 30, get_string('title', 'certificate'));
$pdf->SetTextColor(0, 0, 0);
cert_printtext($pdf, $x, $y + 55, 'C', 'Times', '', 20, get_string('certify', 'certificate'));
cert_printtext($pdf, $x, $y + 105, 'C', 'Helvetica', '', 30, $studentname);
cert_printtext($pdf, $x, $y + 155, 'C', 'Helvetica', '', 20, get_string('statement', 'certificate'));
cert_printtext($pdf, $x, $y + 205, 'C', 'Helvetica', '', 20, $classname);
cert_printtext($pdf, $x, $y + 255, 'C', 'Helvetica', '', 14, $certificatedate);
cert_printtext($pdf, $x, $y + 283, 'C', 'Times', '', 10, $grade);
cert_printtext($pdf, $x, $y + 311, 'C', 'Times', '', 10, $outcome);
cert_printtext($pdf, $x, $y + 339, 'C', 'Times', '', 10, $credithours);
cert_printtext($pdf, $x, $codey, 'C', 'Times', '', 10, $code);
$i = 0;
if ($certificate->printteacher) {
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
        foreach ($teachers as $teacher) {
            $i++;
            cert_printtext($pdf, $sigx, $sigy + ($i * 12), 'L', 'Times', '', 12, fullname($teacher));
        }
    }
}

cert_printtext($pdf, $custx, $custy, 'L', '', '', '', $certificate->customtext);
?>