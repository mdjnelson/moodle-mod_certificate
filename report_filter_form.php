<?php

// This file is developed by Rene van Beekveld of Bright Alley

/**
* Instance add/edit form
*
* @package    mod
* @subpackage certificate
* @copyright  Rene van Beekveld (rene.vanbeekveld@brightalley.nl)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');

class mod_certificate_report_filter_form extends moodleform {

    function definition() {
        global $USER, $CFG;

        $mform = $this->_form;

        // Add header
        $mform->addElement('header', '', get_string('search', 'certificate'), '');
        
        // Add text field: search for
        $mform->addElement('text', 'namesearch', get_string('searchbyname', 'certificate'));
        
        // Add the id of the course
        $mform->addElement('hidden', 'id', $this->_customdata['id']);

        // buttons
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('search', 'certificate'));
        $buttonarray[] = $mform->createElement('submit', 'resetbutton', get_string('reset', 'certificate'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }

    /**
     * Some basic validation
     *
     * @param $data
     * @param $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }
}