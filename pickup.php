<?php

/**
 * picks up a certificate from its issue code
 * modified by: tim st.clair (email@timstclair.me)
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Chardelle Busch, Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->libdir/pdflib.php");

GLOBAL $DB;

	$action = strtoupper(required_param('action', PARAM_TEXT)); // D=download, I=load in browser

	// DEBUGGING, use the numeric id
	// $id = required_param('id', PARAM_INT);
	// $certrecord = $DB->get_record('certificate_issues', array('id' => $id)); // array('code' => $code)
	
	// the certificate_issues table tells us the certificate and the user
	$code = required_param('code', PARAM_TEXT); // certificate issue id
	$certrecord = $DB->get_record('certificate_issues', array('code' => $code));

	// Get the user object (not logging them on)
	$USER = get_complete_user_data('id', $certrecord->userid);

	// certificate is used by the renderer and also gives us the course
	$certificate = $DB->get_record('certificate', array('id' => $certrecord->certificateid));

	// the course lets us get the coursemodule, which in turn gives us the context
	$course = $DB->get_record('course', array('id'=> $certificate->course));
	
	// I'm assuming for simplicity that there's only going to be one certificate in a course
    $params = array();
    $params['courseid'] = $course->id;
    $params['name'] = 'certificate';
	$cm = $DB->get_record_sql("SELECT cm.* FROM {course_modules} cm
								  INNER JOIN {modules} m ON cm.module = m.id 
                                  WHERE cm.course = :courseid
                                  AND m.name = :name
                                  AND m.visible = 1", $params);

	// get the context
	$context = get_context_instance(CONTEXT_MODULE, $cm->id);

	// do some normal moodle page stuff; not sure if we should bother
	$PAGE->set_url('/mod/certificate/grab.php', array('id' => $id));
	$PAGE->set_context($context);
	$PAGE->set_cm($cm);
	$PAGE->set_title(format_string($certificate->name));
	$PAGE->set_heading(format_string($course->fullname));

	// this draws the configured certificate
	require("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");

	// work out the PDF filename to use
    $certname = rtrim($certificate->name, '.');
    $filename = clean_filename("$certname.pdf");
    if ($certificate->savecert == 1) {
        // PDF contents are now in $file_contents as a string
       $file_contents = $pdf->Output('', 'S');
       certificate_save_pdf($file_contents, $certrecord->id, $filename, $context->id);
    }

    $pdf->Output($filename, $action); 
