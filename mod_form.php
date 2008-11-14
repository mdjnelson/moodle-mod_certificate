<?php
require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/certificate/lib.php');
class mod_certificate_mod_form extends moodleform_mod {

	function definition() {

		global $CFG, $COURSE, $form;
		$mform    =& $this->_form;

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('certificatename', 'certificate'), array('size'=>'64'));
		$mform->setType('name', PARAM_TEXT);
		$mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('htmleditor', 'intro', get_string('intro', 'certificate'));
        $mform->setType('intro', PARAM_RAW);
        $mform->setHelpButton('intro', array('writing', 'questions', 'text'), false, 'editorhelpbutton');

//-------------------------------------------------------------------------------
		$mform->addElement('header', 'issueoptions', get_string('issueoptions', 'certificate'));
		$ynoptions = array( 0 => get_string('no'), 1 => get_string('yes'));
		$mform->addElement('select', 'emailteachers', get_string('emailteachers', 'certificate'), $ynoptions);
        $mform->setDefault('emailteachers', 0);
	    $mform->setHelpButton('emailteachers', array('emailteachers', get_string('emailteachers', 'certificate'), 'certificate'));

        $mform->addElement('text', 'emailothers', get_string('emailothers', 'certificate'), array('size'=>'40', 'maxsize'=>'200'));
		$mform->setType('emailothers', PARAM_TEXT);
	    $mform->setHelpButton('emailothers', array('emailothers', get_string('emailothers', 'certificate'), 'certificate'));

        $deliveryoptions = array( 0 => get_string('openbrowser', 'certificate'), 1 => get_string('download', 'certificate'), 2 => get_string('emailcertificate', 'certificate'));
        $mform->addElement('select', 'delivery', get_string('deliver', 'certificate'), $deliveryoptions);
        $mform->setDefault('delivery', 0);
	    $mform->setHelpButton('delivery', array('delivery', get_string('deliver', 'certificate'), 'certificate'));

        $mform->addElement('select', 'savecert', get_string('savecertificate', 'certificate'), $ynoptions);
        $mform->setDefault('savecert', 0);
	    $mform->setHelpButton('savecert', array('save', get_string('savecertificate', 'certificate'), 'certificate'));

        $reportfile = "$CFG->dirroot/certificates/index.php";
        if (file_exists($reportfile)) {
        $mform->addElement('select', 'reportcert', get_string('reportcertificate', 'certificate'), $ynoptions);
        $mform->setDefault('reportcert', 0);
	    $mform->setHelpButton('reportcert', array('reportcert', get_string('reportcertificate', 'certificate'), 'certificate'));
		}
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'lockingoptions', get_string('lockingoptions', 'certificate'));

        $this->restrictoptions = array();
        $this->restrictoptions[0]  = get_string('no');
        for ($i = 100; $i > 0; $i--) {
            $this->restrictoptions[$i] = $i.'%';
        }
		$mform->addElement('select', 'requiredgrade', get_string('requiredgrade', 'certificate'), $this->restrictoptions);
        $mform->setHelpButton('requiredgrade', array('requiredgrade', get_string('requiredgrade', 'certificate'), 'certificate'));

        $this->activities = certificate_get_possible_linked_activities($COURSE, $form->instance);
        $this->linkedacts = certificate_get_linked_activities($form->instance);

        $mform->addElement('text', 'coursetime', get_string('coursetimedependency', 'certificate'), array('size'=>'3'));
        $mform->setDefault('coursetime', isset($this->linkedacts[CERTCOURSETIMEID]) ? $this->linkedacts[CERTCOURSETIMEID]->linkgrade : 0);
	    $mform->setHelpButton('coursetime', array('coursetime', get_string('coursetime', 'certificate'), 'certificate'));
        $formgroup = array();
        $formgroup[] =& $mform->createElement('static', 'linkedactlabel', 'Linked Activity', get_string('linkedactivity', 'certificate'));
        $formgroup[] =& $mform->createElement('static', 'linkedactgrade', 'Minimum Grade %', get_string('minimumgrade', 'certificate'));
        $mform->addGroup($formgroup, 'actlabel', get_string('activitydependencies', 'certificate'), array(' '), false);
        $mform->setHelpButton('actlabel', array('lockedmod', get_string('activitydependencies', 'certificate'), 'certificate'));

/// The linked activities portion goes here, but is forced in in the 'definition_after_data' function so that we can get any elements added in the form and not overwrite them with what's in the database.

        $mform->addElement('submit', 'addlink', get_string('addlinklabel', 'certificate'),
                           array('title' => get_string('addlinktitle', 'certificate')));
        $mform->registerNoSubmitButton('addlink');

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'textoptions', get_string('textoptions', 'certificate'));

        $dateoptions = array( 0 => get_string('no'), 1 => get_string('receiveddate', 'certificate'), 2 => get_string('courseenddate', 'certificate'));
        $mform->addElement('select', 'printdate', get_string('printdate', 'certificate'), $dateoptions);
        $mform->setDefault('printdate', 0);
	    $mform->setHelpButton('printdate', array('printdate', get_string('datehelp', 'certificate'), 'certificate'));

        $dateformatoptions = array( 1 => 'January 1, 2000', 2 => 'January 1st, 2000', 3 => '1 January 2000', 4 => 'January 2000', 5 => get_string('userdateformat', 'certificate'));
        $mform->addElement('select', 'datefmt', get_string('dateformat', 'certificate'), $dateformatoptions);
        $mform->setDefault('datefmt', 0);
	    $mform->setHelpButton('datefmt', array('datefmt', get_string('datehelp', 'certificate'), 'certificate'));

        $mform->addElement('select', 'printnumber', get_string('printcode', 'certificate'), $ynoptions);
        $mform->setDefault('printnumber', 0);
	    $mform->setHelpButton('printnumber', array('printnumber', get_string('printcode', 'certificate'), 'certificate'));

        $gradeoptions = certificate_get_mod_grades($COURSE);
		$mform->addElement('select', 'printgrade', get_string('printgrade', 'certificate'),$gradeoptions);
        $mform->setDefault('printgrade', 0);
	    $mform->setHelpButton('printgrade', array('grade', get_string('grade', 'certificate'), 'certificate'));

        $gradeformatoptions = array( 1 => get_string('gradepercent', 'certificate'), 2 => get_string('gradepoints', 'certificate'), 3 => get_string('gradeletter', 'certificate'));
		$mform->addElement('select', 'gradefmt', get_string('gradeformat', 'certificate'), $gradeformatoptions);
        $mform->setDefault('gradefmt', 0);
	    $mform->setHelpButton('gradefmt', array('gradefmt', get_string('grade', 'certificate'), 'certificate'));

        $outcomeoptions = certificate_get_outcomes($COURSE);
		$mform->addElement('select', 'printoutcome', get_string('printoutcome', 'certificate'),$outcomeoptions);
        $mform->setDefault('printoutcome', 0);
	    $mform->setHelpButton('printoutcome', array('outcome', get_string('printoutcome', 'certificate'), 'certificate'));

        $mform->addElement('text', 'printhours', get_string('printhours', 'certificate'), array('size'=>'5'));
		$mform->setType('printhours', PARAM_TEXT);
	    $mform->setHelpButton('printhours', array('printhours', get_string('printhours', 'certificate'), 'certificate'));

        $mform->addElement('select', 'printteacher', get_string('printteacher', 'certificate'), $ynoptions);
        $mform->setDefault('printteacher', 0);
	    $mform->setHelpButton('printteacher', array('printteacher', get_string('printteacher', 'certificate'), 'certificate'));

		$mform->addElement('textarea', 'customtext', get_string('customtext', 'certificate'), array('cols'=>'40', 'rows'=>'4', 'wrap'=>'virtual'));
		$mform->setType('customtext', PARAM_RAW);
	    $mform->setHelpButton('customtext', array('customtext', get_string('customtext', 'certificate'), 'certificate'));


//-------------------------------------------------------------------------------
        $mform->addElement('header', 'designoptions', get_string('designoptions', 'certificate'));
        $CERTIFICATE_TYPES = certificate_types();
        $mform->addElement('select', 'certificatetype', get_string('certificatetype', 'certificate'),$CERTIFICATE_TYPES);
        $mform->setDefault('certificatetype', 'landscape');
	    $mform->setHelpButton('certificatetype', array('types', get_string('certificatetype', 'certificate'), 'certificate'));

        $borderstyleoptions = certificate_get_borders();
		$mform->addElement('select', 'borderstyle', get_string('borderstyle', 'certificate'), $borderstyleoptions);
        $mform->setDefault('borderstyle', 0);
	    $mform->setHelpButton('borderstyle', array('border', get_string('border', 'certificate'), 'certificate'));

        $printframe = array( 0 => get_string('no'), 1 => get_string('borderblack', 'certificate'), 2 => get_string('borderbrown', 'certificate'), 3 => get_string('borderblue', 'certificate'), 4 => get_string('bordergreen', 'certificate'));
        $mform->addElement('select', 'bordercolor', get_string('bordercolor', 'certificate'), $printframe);
        $mform->setDefault('bordercolor', 0);
	    $mform->setHelpButton('bordercolor', array('borderline', get_string('bordercolor', 'certificate'), 'certificate'));

        $wmarkoptions = certificate_get_watermarks();
        $mform->addElement('select', 'printwmark', get_string('printwmark', 'certificate'),$wmarkoptions);
        $mform->setDefault('printwmark', 0);
	    $mform->setHelpButton('printwmark', array('watermark', get_string('printwmark', 'certificate'), 'certificate'));

		$signatureoptions = certificate_get_signatures ();
		$mform->addElement('select', 'printsignature', get_string('printsignature', 'certificate'), $signatureoptions);
        $mform->setDefault('printsignature', 0);
	    $mform->setHelpButton('printsignature', array('signature', get_string('printsignature', 'certificate'), 'certificate'));

		$sealoptions = certificate_get_seals();
		$mform->addElement('select', 'printseal', get_string('printseal', 'certificate'),$sealoptions);
        $mform->setDefault('printseal', 0);
	    $mform->setHelpButton('printseal', array('seal', get_string('printseal', 'certificate'), 'certificate'));
//-------------------------------------------------------------------------------
        $features = new stdClass;
        $features->groups = true;
        $features->groupings = true;
        $features->groupmembersonly = true;
		$features->gradecat = false;
		$features->gradecat = false;
        $features->idnumber = false;
        $this->standard_coursemodule_elements($features);

        $this->add_action_buttons();

	}

/**
 * Add the linked activities portion only after the entire form has been created. That way,
 * we can act on previous added values that haven't been committed to the database.
 * Check for an 'addlink' button. If the linked activities fields are all full, add an empty one.
 */
    function definition_after_data() {
        global $form;

        /// This gets called more than once, and there's no way to tell which time this is, so set a
        /// variable to make it as called so we only do this processing once.
        if (!empty($this->def_after_data_done)) {
            return;
        }
        $this->def_after_data_done = true;

        $mform    =& $this->_form;
        $fdata = $mform->getSubmitValues();

    /// Get the existing linked activities from the database, unless this form has resubmitted itself, in
    /// which case they will be in the form already.
        $linkids = array();
        $linkgrade = array();
        $linkentry = array();

        if (empty($fdata)) {
            if ($linkedacts = certificate_get_linked_activities($form->instance)) {
                foreach ($linkedacts as $idx => $linkedact) {
                    if ($idx != CERTCOURSETIMEID) {
                        $linkids[] = $linkedact->linkid;
                        $linkgrades[] = $linkedact->linkgrade;
                        $linkentry[] = $linkedact->id;
                    }
                }
            }
        } else {
            foreach ($fdata['linkid'] as $idx => $linkid) {
                $linkids[$idx] = $linkid;
                $linkgrades[$idx] = $fdata['linkgrade'][$idx];
                if (!empty($fdata['linkentry'][$idx])) {
                    $linkentry[$idx] = $fdata['linkentry'][$idx];
                }
            }
        }

        foreach ($linkids as $idx => $linkid) {
            $formgroup = array();
            $formgroup[] =& $mform->createElement('select', 'linkid['.$idx.']', '', $this->activities);
            $mform->setDefault('linkid['.$idx.']', $linkid);
            $formgroup[] =& $mform->createElement('select', 'linkgrade['.$idx.']', '', $this->restrictoptions);
            $mform->setDefault('linkgrade['.$idx.']', $linkgrades[$idx]);
            $group =& $mform->createElement('group', 'actlab'.$idx, ($idx+1), $formgroup, array(' '), false);
            $mform->insertElementBefore($group, 'addlink');
            if (!empty($linkentry[$idx])) {
                $mform->addElement('hidden', 'linkentry['.$idx.']', $linkentry[$idx]);
            }
        }

        $numlacts = count($linkids);
        $formgroup = array();
        $formgroup[] =& $mform->createElement('select', 'linkid['.$numlacts.']', '', $this->activities);
        $mform->setDefault('linkid['.$numlacts.']', 0);
        $formgroup[] =& $mform->createElement('select', 'linkgrade['.$numlacts.']', '', $this->restrictoptions);
        $mform->setDefault('linkgrade['.$numlacts.']', '');
        $group =& $mform->createElement('group', 'actlab'.$numlacts, ($numlacts+1), $formgroup, array(' '), false);
        $mform->insertElementBefore($group, 'addlink');

    }
}
?>