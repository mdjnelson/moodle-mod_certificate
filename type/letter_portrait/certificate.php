<?php  

// Load certificate info
$certificateid = $certificate->id;
$certrecord = certificate_get_issue($course, $USER, $certificateid);
$strreviewcertificate = get_string('reviewcertificate', 'certificate');
$strgetcertificate = get_string('getcertificate', 'certificate');
$strgrade = get_string('grade', 'certificate');
$strcoursegrade = get_string('coursegrade', 'certificate');

// Date formatting - can be customized if necessary
setlocale (LC_TIME, '');
$certificatedate = '';
if ($certrecord) {
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
$coursegrade = get_course_grade($course->id);    
    if($certificate->printgrade > 0) {
    if($certificate->printgrade == 1) {
    if($certificate->gradefmt == 1) {
    $grade = $strcoursegrade.':  '.$coursegrade->percentage.'%';
}   if($certificate->gradefmt == 2) {
    $grade = 'Course Grade:  '.$coursegrade->points;
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

//Print the first three ordered teacher's names for the course
$teachername1 = '';
$teachername2 = '';
$teachername3 = '';
if($certificate->printteacher && $teachers) {
            foreach ($teachers as $teacher) {
            if ($teacher->authority == 1) {
            $teachername1 = fullname($teacher);
            } 
            if ($teacher->authority == 2) {
            $teachername2 = fullname($teacher);
            } 
            if ($teacher->authority == 3) {
            $teachername3 = fullname($teacher);
            }
    } 
}
// Print the code number
$code = '';
if($certificate->printnumber) {
if ($certrecord) {
$code = $certrecord->code;
}
}
//Print the student name
$studentname = '';
if ($certrecord) {
$studentname = $certrecord->studentname;
}

// Add pdf page
    $orientation = "P";
    $pdf=new FPDF_Protection($orientation, 'pt', 'Letter');
    $pdf->SetProtection(array('print'));
    $pdf->AddPage();
    
// Add images
    $color = $certificate->bordercolor;
    print_border_letter($certificate->borderstyle, $color, $orientation);
    print_watermark_letter($certificate->printwmark, $orientation);
    print_seal($certificate->printseal, $orientation, 440, 590, 80, 80);
    print_signature($certificate->printsignature, $orientation, 98, 530, '', '');

// Add text
    $pdf->SetTextColor(0,0,128);
    cert_printtext(58, 170, 'C', 'Arial', 'B', 28, get_string("titleportrait", "certificate"));
    $pdf->SetTextColor(0,0,0);
    cert_printtext(58, 230, 'C', 'Arial', 'B', 20, get_string("introportrait", "certificate"));
    cert_printtext(58, 280, 'C', 'Arial', '', 30, utf8_decode($studentname));
    cert_printtext(58, 330, 'C', 'Arial', '', 20, get_string("statementportrait", "certificate"));
    cert_printtext(58, 380, 'C', 'Arial', '', 20, utf8_decode($course->fullname));
    cert_printtext(58, 420, 'C', 'Arial', '', 20, get_string("ondayportrait", "certificate"));
    cert_printtext(58, 460, 'C', 'Arial', '', 14, $certificatedate);
    cert_printtext(98, 540, 'L', 'Times', '', 12, utf8_decode($teachername1));
    cert_printtext(98, 560, 'L', 'Times', '', 12, utf8_decode($teachername2));
    cert_printtext(98, 580, 'L', 'Times', '', 12, utf8_decode($teachername3));
    cert_printtext(58, 590, 'C', 'Arial', '', 10, $grade);
    cert_printtext(58, 640, 'C', 'Helvetica', '', 10, $code);
?>