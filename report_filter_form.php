<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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
        $mform->addElement('header', '', get_string('filter', 'certificate'), '');
        
        // Add text field: search for
        $mform->addElement('text', 'namesearch', get_string('searchfor', 'certificate'));
        
        // Add the id of the course
        $mform->addElement('hidden', 'id', $this->_customdata['id']);

        // buttons
        $this->add_action_buttons(false, get_string('search', 'certificate'));
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