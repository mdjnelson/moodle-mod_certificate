<?PHP  // Created by Hugo Salgado <hsalgado@vulcano.cl>

/// This page prints a particular instance of certificate

require_once("../../config.php");
require_once("lib.php");

include 'class.ezpdf.php';

optional_variable($id);    // Course Module ID, or
optional_variable($a);     // DIPLOMA ID

if ($id) {
    if (! $cm = get_record("course_modules", "id", $id)) {
        error("Course Module ID was incorrect");
    }

    if (! $course = get_record("course", "id", $cm->course)) {
        error("Course is misconfigured");
    }

    if (! $certificate = get_record("certificate", "id", $cm->instance)) {
        error("Course module is incorrect");
    }
}
else {
    if (! $certificate = get_record("certificate", "id", $a)) {
        error("Course module is incorrect");
    }
    if (! $course = get_record("course", "id", $certificate->course)) {
        error("Course is misconfigured");
    }
    if (! $cm = get_coursemodule_from_instance("certificate", $certificate->id, $course->id)) {
        error("Course Module ID was incorrect");
    }
}

require_login($course->id);

add_to_log($course->id, "certificate", "view", "view.php?id=$cm->id", "$certificate->id");

/// Print the page header

if ($course->category) {
    $navigation = "<A HREF=\"../../course/view.php?id=$course->id\">$course->shortname</A> ->";
}

$strcertificates = get_string("modulenameplural", "certificate");
$strcertificate  = get_string("modulename", "certificate");

if (isteacher($course->id)) {
    print_header("$course->shortname: $certificate->name", "$course->fullname",
       "$navigation <A HREF=index.php?id=$course->id>$strcertificates</A> -> $certificate->name", 
       "", "", true, update_module_button($cm->id, $course->id, $strcertificate), 
       navmenu($course, $cm));

    echo get_string("advertencia", "certificate");

    print_footer($course);

}
else {

    // Ok, here we have a student. We must create the PDF
    $pdf = new Cezpdf('a4','landscape');

    // Adding the border and the 2 signatures
    $pdf->addPngFromFile('borde.png',25,20,800,550);
    $pdf->addPngFromFile('signature.png',480,115,200,100);
    $pdf->addPngFromFile('signature2.png',150,110,200,100);

    // Adding the title
    $pdf->selectFont('./fonts/Helvetica');
    $pdf->ezSetDy(-100);
    $strcertificate = get_string("diploma", "certificate");
    $pdf->ezText("<b>$strcertificate</b>",30,array('justification'=>'centre'));

    // Adding the first line "This diploma has been conferred for...."
    $pdf->ezSetDy(-30);
    $strlinea1 = get_string("linea1", "certificate");
    $pdf->ezText($strlinea1,20,array('justification'=>'centre'));

    // Adding the student's names
    $pdf->selectFont('./fonts/Helvetica-BoldOblique');
    $pdf->ezSetDy(-20);
    $pdf->ezText($USER->firstname . ' ' . $USER->lastname,24,array('justification'=>'centre'));

    // Adding the second line "who has satisfactorily..."
    $pdf->selectFont('./fonts/Helvetica');
    $pdf->ezSetDy(-20);
    $strlinea2 = get_string("linea2", "certificate");
    $pdf->ezText($strlinea2,20,array('justification'=>'centre'));

    // Adding the course name
    $pdf->selectFont('./fonts/Helvetica-BoldOblique');
    $name = urldecode($n);
    $pdf->ezSetDy(-10);
    $pdf->ezText($course->fullname,22,array('justification'=>'centre'));

    // We're ready. This print the appropiate header and content
    $pdf->stream();
}

?>
