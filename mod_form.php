<?php
require_once ('moodleform_mod.php');
require_once("$CFG->dirroot/mod/certificate/lib.php");
class mod_certificate_mod_form extends moodleform_mod {

	function definition() {

		global $COURSE;
		$mform    =& $this->_form;

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('certificatename', 'certificate'), array('size'=>'64'));
		$mform->setType('name', PARAM_TEXT);
		$mform->addRule('name', null, 'required', null, 'client');
//-------------------------------------------------------------------------------
		$mform->addElement('header', 'issueoptions', get_string('issueoptions', 'certificate'));		
		$ynoptions = array( 0 => get_string('no'), 1 => get_string('yes'));
		$mform->addElement('select', 'emailteachers', get_string('emailteachers', 'certificate'), $ynoptions);
        $mform->setDefault('emailteachers', 0);
	    $mform->setHelpButton('emailteachers', array('emailteachers', get_string('emailteachers', 'certificate'), 'certificate'));
	
        $mform->addElement('select', 'savecertificate', get_string('savecertificate', 'certificate'), $ynoptions);
        $mform->setDefault('savecert', 0);
	    $mform->setHelpButton('savecertificate', array('savecert', get_string('savecertificate', 'certificate'), 'certificate'));
		
        unset($options);
          $options[0]    = get_string('openbrowser', 'certificate');
          $options[1]  = get_string('download', 'certificate');
          $options[2]  = get_string('emailcertificate', 'certificate');
        $mform->addElement('select', 'deliver', get_string('deliver', 'certificate'), $options);
        $mform->setDefault('deliver', 0);
	    $mform->setHelpButton('deliver', array('deliver', get_string('deliver', 'certificate'), 'certificate'));
		
		
		
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'textoptions', get_string('textoptions', 'certificate'));		

        $dateoptions = array( 0 => get_string('no'), 1 => get_string('receiveddate', 'certificate'), 2 => get_string('courseenddate', 'certificate'));
        $mform->addElement('select', 'printdate', get_string('printdate', 'certificate'), $dateoptions);
        $mform->setDefault('printdate', 0);
	    $mform->setHelpButton('printdate', array('date', get_string('datehelp', 'certificate'), 'certificate'));
 
        $dateformatoptions = array( 1 => 'January 1, 2000', 2 => 'January 1st, 2000', 3 => '1 January 2000', 4 => 'January 2000');
        $mform->addElement('select', 'dateformat', get_string('dateformat', 'certificate'), $dateformatoptions);
        $mform->setDefault('datefmt', 0);
	    $mform->setHelpButton('dateformat', array('date', get_string('datehelp', 'certificate'), 'certificate'));

        $mform->addElement('select', 'printcode', get_string('printcode', 'certificate'), $ynoptions);
        $mform->setDefault('printnumber', 0);
	    $mform->setHelpButton('printcode', array('code', get_string('printcode', 'certificate'), 'certificate'));

        $gradeoptions = certificate_get_mod_grades($course);
		$mform->addElement('select', 'printgrade', get_string('printgrade', 'certificate'),$gradeoptions);
        $mform->setDefault('printgrade', 0);
	    $mform->setHelpButton('printgrade', array('grade', get_string('grade', 'certificate'), 'certificate'));

        $gradeformatoptions = array( 1 => get_string('gradepercent', 'certificate'), 2 => get_string('gradepoints', 'certificate'), 3 => get_string('gradeletter', 'certificate'));
		$mform->addElement('select', 'gradeformat', get_string('gradeformat', 'certificate'), $gradeformatoptions);
        $mform->setDefault('gradefmt', 0);
	    $mform->setHelpButton('gradeformat', array('grade', get_string('grade', 'certificate'), 'certificate'));

        $mform->addElement('select', 'printteacher', get_string('printteacher', 'certificate'), $ynoptions);
        $mform->setDefault('printteacher', 0);
	    $mform->setHelpButton('printteacher', array('teacher', get_string('printteacher', 'certificate'), 'certificate'));

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'designoptions', get_string('designoptions', 'certificate'));		
        $CERTIFICATE_TYPES = certificate_types();
        $mform->addElement('select', 'certificatetype', get_string('certificatetype', 'certificate'),$CERTIFICATE_TYPES);
        $mform->setDefault('certificatetype', 'landscape');
	    $mform->setHelpButton('certificatetype', array('types', get_string('certificatetype', 'certificate'), 'certificate'));

        $borderstyleoptions = array( 0 => get_string('no'));
		$mform->addElement('select', 'borderstyle', get_string('borderstyle', 'certificate'), $borderstyleoptions);
        $mform->setDefault('borderstyle', 0);
	    $mform->setHelpButton('borderstyle', array('border', get_string('border', 'certificate'), 'certificate'));		

        $coloroptions = array( 0 => get_string('borderblack', 'certificate'), 1 => get_string('borderbrown', 'certificate'), 2 => get_string('borderblue', 'certificate'), 3 => get_string('bordergreen', 'certificate'));
        $mform->addElement('select', 'bordercolor', get_string('bordercolor', 'certificate'), $coloroptions);
        $mform->setDefault('bordercolor', 0);
	    $mform->setHelpButton('borderstyle', array('border', get_string('border', 'certificate'), 'certificate'));


        $wmarkoptions = array( 0 => get_string('no'));
        $mform->addElement('select', 'printwmark', get_string('printwmark', 'certificate'),$wmarkoptions);
        $mform->setDefault('printwmark', 0);
	    $mform->setHelpButton('printwmark', array('watermark', get_string('printwmark', 'certificate'), 'certificate'));	
		
		$signatureoptions = array( 0 => get_string('no'));
		$mform->addElement('select', 'printsignature', get_string('printsignature', 'certificate'), $signatureoptions);
        $mform->setDefault('printsignature', 0);
	    $mform->setHelpButton('printsignature', array('signature', get_string('printsignature', 'certificate'), 'certificate'));		
		
        $sealoptions = array( 0 => get_string('no'));
		$mform->addElement('select', 'printseal', get_string('printseal', 'certificate'),$sealoptions);
        $mform->setDefault('printseal', 0);
	    $mform->setHelpButton('printseal', array('seal', get_string('printseal', 'certificate'), 'certificate'));					
//-------------------------------------------------------------------------------
		$this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // buttons
        $this->add_action_buttons();

	}



}
?>