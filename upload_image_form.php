<?php

/**
 * Handles uploading files
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');

class mod_certificate_upload_image_form extends moodleform {

    function definition() {
        global $CFG;

        $mform =& $this->_form;

        $imagetypes = array(
            CERT_IMAGE_BORDER => get_string('border', 'certificate'),
            CERT_IMAGE_WATERMARK => get_string('watermark', 'certificate'),
            CERT_IMAGE_SIGNATURE => get_string('signature', 'certificate'),
            CERT_IMAGE_SEAL => get_string('seal', 'certificate')
        );

        $mform->addElement('select', 'imagetype', get_string('imagetype', 'certificate'), $imagetypes);

        $mform->addElement('filepicker', 'certificateimage', '');
        $mform->addRule('certificateimage', null, 'required', null, 'client');

        $this->add_action_buttons();
    }

    /**
     * Some validation - Michael Avelar <michaela@moodlerooms.com>
     */
    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $supportedtypes = array('jpe' => 'image/jpeg',
                                'jpeIE' => 'image/pjpeg',
                                'jpeg' => 'image/jpeg',
                                'jpegIE' => 'image/pjpeg',
                                'jpg' => 'image/jpeg',
                                'jpgIE' => 'image/pjpeg',
                                'png' => 'image/png',
                                'pngIE' => 'image/x-png');

        $files = $this->get_draft_files('certificateimage');
        if ($files) {
            foreach ($files as $file) {
                if (!in_array($file->get_mimetype(), $supportedtypes)) {
                    $errors['certificateimage'] = get_string('unsupportedfiletype', 'certificate');
                }
            }
        } else {
            $errors['certificateimage'] = get_string('nofileselected', 'certificate');
        }

        return $errors;
    }
}