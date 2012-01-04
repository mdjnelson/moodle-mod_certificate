<?php
/**
 * Settings file upload popup
 *
 * @author Michael Avelar <michaela@moodlerooms.com>
 * @version $Id$
 * @package mod/certificate
 **/

require_once('../../config.php');
require_once($CFG->dirroot.'/mod/certificate/upload_image_form.php');

require_login(SITEID);

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('moodle/site:config', $context);

$PAGE->set_url('/mod/certificate/upload_image.php');

$mform = new mod_certificate_upload_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'));
} else if ($data = $mform->get_data()) {
    /** @var $data stdClass */
    $pixpaths    = mod_certificate_pix_handler::$pixpaths;
    $destination = mod_certificate_pix_handler::get_certmod_pixpath();
    $filename    = $mform->get_new_filename('certificate_upload');
    $destination = $destination.'/'.$pixpaths[$data->certificate_pix_destination].'/'.$filename;

    if (!$mform->save_file('certificate_upload', $destination, true)) {
        throw new coding_exception('File upload failed');
    }
    redirect(new moodle_url('/admin/settings.php?section=modsettingcertificate'), get_string('changessaved'));
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
