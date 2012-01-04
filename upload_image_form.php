<?php

/**
 * This file defines the main certificate configuration form
 * It uses the standard core Moodle (>1.8) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             newmodule type (index.php) and in the header
 *             of the newmodule main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 *
 * @version $Id$
 * @package mod/certificate
 **/

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/certificate/certpixhandler.class.php');

class mod_certificate_upload_form extends moodleform {

    /**
     * Integer representation of the target upload directory.
     */
    var $destinationdir;

    function definition() {
        global $COURSE, $CFG;
        $mform    =& $this->_form;

        $mform->addElement('header', 'certificate_upload_title', get_string('uploadpixsform', 'certificate'));

        $mform->addElement('filepicker', 'certificate_upload', get_string('chooseuploadpix', 'certificate'), null, array('accepted_types' => mod_certificate_pix_handler::$supportedtypes));
        $mform->addRule('certificate_upload', null, 'required', null, 'client');

        $options = array('' => get_string('choose').'...');
        $options += mod_certificate_pix_handler::$pixpaths;
        $mform->addElement('select', 'certificate_pix_destination', get_string('pixdestination', 'certificate'), $options, '');
        $mform->addRule('certificate_pix_destination', null, 'required', null, 'client');

        $this->add_action_buttons();

    }

    /**
     * Override to verify that submitted file is a valid image
     */
    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $supportedtypes = mod_certificate_pix_handler::$supportedtypes;

        /** @var $files stored_file[]|boolean */
        $files = $this->get_draft_files('certificate_upload');
        if ($files) {
            foreach ($files as $file) {
                if (!in_array($file->get_mimetype(), $supportedtypes)) {
                    $errors['certificate_upload'] = get_string('unsupportedfiletype', 'certificate');
                }
            }
        } else {
            $errors['certificate_upload'] = get_string('nofileselected', 'certificate');
        }
        return $errors;
    }
}
?>