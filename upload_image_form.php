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
 * Handles uploading files
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');

class mod_certificate_upload_image_form extends moodleform {

    function definition() {
        global $CFG,$imagetypes;

        $mform =& $this->_form;

        $context = context_system::instance();
        foreach($imagetypes as $imagetype=>$label) {
            $entry = new stdClass;
            $entry->id = null;
            $mform->addElement('filemanager', $imagetype, $label, null,
                array('accepted_types' => array('web_image')));
            $draftitemid = file_get_submitted_draft_itemid($imagetype);
            file_prepare_draft_area($draftitemid, $context->id, 'mod_certificate', $imagetype, 0,
                array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 50));
            $entry->$imagetype = $draftitemid;
            $this->set_data($entry);
        }


        $this->add_action_buttons();
    }

    /**
     * Some validation - Michael Avelar <michaela@moodlerooms.com>
     */
    function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}