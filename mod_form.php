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
* @package    mod_certificate
* @copyright  Mark Nelson <markn@moodle.com>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/certificate/locallib.php');

class mod_certificate_mod_form extends moodleform_mod {

    function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('certificatename', 'certificate'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements(get_string('intro', 'certificate'));

        // Issue options
        $mform->addElement('header', 'issueoptions', get_string('issueoptions', 'certificate'));
        $ynoptions = array( 0 => get_string('no'), 1 => get_string('yes'));
        $mform->addElement('select', 'emailteachers', get_string('emailteachers', 'certificate'), $ynoptions);
        $mform->setDefault('emailteachers', 0);
        $mform->addHelpButton('emailteachers', 'emailteachers', 'certificate');

        $mform->addElement('text', 'emailothers', get_string('emailothers', 'certificate'), array('size'=>'40', 'maxsize'=>'200'));
        $mform->setType('emailothers', PARAM_TEXT);
        $mform->addHelpButton('emailothers', 'emailothers', 'certificate');

        $deliveryoptions = array( 0 => get_string('openbrowser', 'certificate'), 1 => get_string('download', 'certificate'), 2 => get_string('emailcertificate', 'certificate'));
        $mform->addElement('select', 'delivery', get_string('delivery', 'certificate'), $deliveryoptions);
        $mform->setDefault('delivery', 0);
        $mform->addHelpButton('delivery', 'delivery', 'certificate');

        $mform->addElement('select', 'savecert', get_string('savecert', 'certificate'), $ynoptions);
        $mform->setDefault('savecert', 0);
        $mform->addHelpButton('savecert', 'savecert', 'certificate');

        $reportfile = "$CFG->dirroot/certificates/index.php";
        if (file_exists($reportfile)) {
            $mform->addElement('select', 'reportcert', get_string('reportcert', 'certificate'), $ynoptions);
            $mform->setDefault('reportcert', 0);
            $mform->addHelpButton('reportcert', 'reportcert', 'certificate');
        }

        $mform->addElement('text', 'requiredtime', get_string('coursetimereq', 'certificate'), array('size'=>'3'));
        $mform->setType('requiredtime', PARAM_INT);
        $mform->addHelpButton('requiredtime', 'coursetimereq', 'certificate');

        // Text Options
        $mform->addElement('header', 'textoptions', get_string('textoptions', 'certificate'));

        $modules = certificate_get_mods();
        $dateoptions = certificate_get_date_options() + $modules;
        $mform->addElement('select', 'printdate', get_string('printdate', 'certificate'), $dateoptions);
        $mform->setDefault('printdate', 'N');
        $mform->addHelpButton('printdate', 'printdate', 'certificate');

        $dateformatoptions = array( 1 => 'January 1, 2000', 2 => 'January 1st, 2000', 3 => '1 January 2000',
            4 => 'January 2000', 5 => get_string('userdateformat', 'certificate'));
        $mform->addElement('select', 'datefmt', get_string('datefmt', 'certificate'), $dateformatoptions);
        $mform->setDefault('datefmt', 0);
        $mform->addHelpButton('datefmt', 'datefmt', 'certificate');

        $mform->addElement('select', 'printnumber', get_string('printnumber', 'certificate'), $ynoptions);
        $mform->setDefault('printnumber', 0);
        $mform->addHelpButton('printnumber', 'printnumber', 'certificate');

        $gradeoptions = certificate_get_grade_options() + certificate_get_grade_categories($this->current->course) + $modules;
        $mform->addElement('select', 'printgrade', get_string('printgrade', 'certificate'),$gradeoptions);
        $mform->setDefault('printgrade', 0);
        $mform->addHelpButton('printgrade', 'printgrade', 'certificate');

        $gradeformatoptions = array( 1 => get_string('gradepercent', 'certificate'), 2 => get_string('gradepoints', 'certificate'),
            3 => get_string('gradeletter', 'certificate'));
        $mform->addElement('select', 'gradefmt', get_string('gradefmt', 'certificate'), $gradeformatoptions);
        $mform->setDefault('gradefmt', 0);
        $mform->addHelpButton('gradefmt', 'gradefmt', 'certificate');

        $outcomeoptions = certificate_get_outcomes();
        $mform->addElement('select', 'printoutcome', get_string('printoutcome', 'certificate'),$outcomeoptions);
        $mform->setDefault('printoutcome', 0);
        $mform->addHelpButton('printoutcome', 'printoutcome', 'certificate');

        $mform->addElement('text', 'printhours', get_string('printhours', 'certificate'), array('size'=>'5', 'maxlength' => '255'));
        $mform->setType('printhours', PARAM_TEXT);
        $mform->addHelpButton('printhours', 'printhours', 'certificate');

        $mform->addElement('select', 'printteacher', get_string('printteacher', 'certificate'), $ynoptions);
        $mform->setDefault('printteacher', 0);
        $mform->addHelpButton('printteacher', 'printteacher', 'certificate');

        $mform->addElement('textarea', 'customtext', get_string('customtext', 'certificate'), array('cols'=>'40', 'rows'=>'4', 'wrap'=>'virtual'));
        $mform->setType('customtext', PARAM_RAW);
        $mform->addHelpButton('customtext', 'customtext', 'certificate');

        // Design Options
        $mform->addElement('header', 'designoptions', get_string('designoptions', 'certificate'));
        $mform->addElement('select', 'certificatetype', get_string('certificatetype', 'certificate'), certificate_types());
        $mform->setDefault('certificatetype', 'A4_non_embedded');
        $mform->addHelpButton('certificatetype', 'certificatetype', 'certificate');

        $orientation = array( 'L' => get_string('landscape', 'certificate'), 'P' => get_string('portrait', 'certificate'));
        $mform->addElement('select', 'orientation', get_string('orientation', 'certificate'), $orientation);
        $mform->setDefault('orientation', 'L');
        $mform->addHelpButton('orientation', 'orientation', 'certificate');

        $mform->addElement('select', 'borderstyle', get_string('borderstyle', 'certificate'), certificate_get_images(CERT_IMAGE_BORDER));
        $mform->setDefault('borderstyle', '0');
        $mform->addHelpButton('borderstyle', 'borderstyle', 'certificate');

        $printframe = array( 0 => get_string('no'), 1 => get_string('borderblack', 'certificate'), 2 => get_string('borderbrown', 'certificate'),
            3 => get_string('borderblue', 'certificate'), 4 => get_string('bordergreen', 'certificate'));
        $mform->addElement('select', 'bordercolor', get_string('bordercolor', 'certificate'), $printframe);
        $mform->setDefault('bordercolor', '0');
        $mform->addHelpButton('bordercolor', 'bordercolor', 'certificate');

        $mform->addElement('select', 'printwmark', get_string('printwmark', 'certificate'), certificate_get_images(CERT_IMAGE_WATERMARK));
        $mform->setDefault('printwmark', '0');
        $mform->addHelpButton('printwmark', 'printwmark', 'certificate');

        $mform->addElement('select', 'printsignature', get_string('printsignature', 'certificate'), certificate_get_images(CERT_IMAGE_SIGNATURE));
        $mform->setDefault('printsignature', '0');
        $mform->addHelpButton('printsignature', 'printsignature', 'certificate');

        $mform->addElement('select', 'printseal', get_string('printseal', 'certificate'), certificate_get_images(CERT_IMAGE_SEAL));
        $mform->setDefault('printseal', '0');
        $mform->addHelpButton('printseal', 'printseal', 'certificate');

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
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

        // Check that the required time entered is valid
        if ((!is_number($data['requiredtime']) || $data['requiredtime'] < 0)) {
            $errors['requiredtime'] = get_string('requiredtimenotvalid', 'certificate');
        }

        return $errors;
    }
}
