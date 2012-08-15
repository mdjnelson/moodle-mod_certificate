<?php

/**
 * Handles uploading files
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');
require_once($CFG->dirroot.'/mod/certificate/upload_image_form.php');

require_login();

$context = get_system_context();
require_capability('moodle/site:config', $context);

$PAGE->set_url('/mod/certificate/upload_image.php');
$PAGE->set_pagetype('site-index');
$PAGE->set_docs_path('');
$PAGE->set_pagelayout('frontpage');
$PAGE->set_context($context);
$PAGE->set_title(get_string('uploadimage', 'certificate'));

$upload_form = new mod_certificate_upload_image_form();

if ($upload_form->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'));
} else if ($data = $upload_form->get_data()) {
    // Ensure the directory for storing is created
    $uploaddir = "mod/certificate/pix/$data->imagetype";
    $filename = $upload_form->get_new_filename('certificateimage');
    make_upload_directory($uploaddir);
    $destination = $CFG->dataroot . '/' . $uploaddir . '/' . $filename;
    if (!$upload_form->save_file('certificateimage', $destination, true)) {
        throw new coding_exception('File upload failed');
    }

    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'), get_string('changessaved'));
}

echo $OUTPUT->header();
echo $upload_form->display();
echo $OUTPUT->footer();
?>
