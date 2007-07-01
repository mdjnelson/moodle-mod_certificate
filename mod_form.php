<?php
require_once ('moodleform_mod.php');
require_once("$CFG->dirroot/mod/certificate/lib.php");
class mod_certificate_mod_form extends moodleform_mod {

	function definition() {

		global $CFG, $COURSE;
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

        $mform->addElement('text', 'emailothers', get_string('emailothers', 'certificate'), array('size'=>'40', 'maxsize'=>'200'));
		$mform->setType('emailothers', PARAM_TEXT);
	    $mform->setHelpButton('emailothers', array('emailothers', get_string('emailothers', 'certificate'), 'certificate'));
	
        $mform->addElement('select', 'savecert', get_string('savecertificate', 'certificate'), $ynoptions);
        $mform->setDefault('savecert', 0);
	    $mform->setHelpButton('savecert', array('save', get_string('savecertificate', 'certificate'), 'certificate'));

        $deliveryoptions = array( 0 => get_string('openbrowser', 'certificate'), 1 => get_string('download', 'certificate'), 2 => get_string('emailcertificate', 'certificate'));
        $mform->addElement('select', 'delivery', get_string('deliver', 'certificate'), $deliveryoptions);
        $mform->setDefault('delivery', 0);
	    $mform->setHelpButton('delivery', array('delivery', get_string('deliver', 'certificate'), 'certificate'));

        $gradeoptions = certificate_get_mod_grades($COURSE);
$mform->addElement('select', 'lockgrade', get_string('lockgrade', 'certificate'), $gradeoptions);
        $mform->setDefault('lockgrade', 0);
	    $mform->setHelpButton('lockgrade', array('lockgrade', get_string('lockgrade', 'certificate'), 'certificate'));

        $restrictoptions = array();
        $restrictoptions[0]  = get_string('no');
        $restrictoptions[100] = '100%';
        $restrictoptions[99] = '99%';
        $restrictoptions[98] = '98%';
        $restrictoptions[97] = '97%';
        $restrictoptions[96] = '96%';
        $restrictoptions[95] = '95%';
        $restrictoptions[94] = '94%';
        $restrictoptions[93] = '93%';
        $restrictoptions[92] = '92%';
        $restrictoptions[91] = '91%';
        $restrictoptions[90] = '90%';
        $restrictoptions[89] = '89%';
        $restrictoptions[88] = '88%';
        $restrictoptions[87] = '87%';
        $restrictoptions[86] = '86%';
        $restrictoptions[85] = '85%';
        $restrictoptions[84] = '84%';
        $restrictoptions[83] = '83%';
        $restrictoptions[82] = '82%';
        $restrictoptions[81] = '81%';
        $restrictoptions[80] = '80%';
        $restrictoptions[79] = '79%';
        $restrictoptions[78] = '78%';
        $restrictoptions[77] = '77%';
        $restrictoptions[76] = '76%';
        $restrictoptions[75] = '75%';
        $restrictoptions[74] = '74%';
        $restrictoptions[73] = '73%';
        $restrictoptions[72] = '72%';
        $restrictoptions[71] = '71%';
        $restrictoptions[70] = '70%';
        $restrictoptions[69] = '69%';
        $restrictoptions[68] = '68%';
        $restrictoptions[67] = '67%';
        $restrictoptions[66] = '66%';
        $restrictoptions[65] = '65%';
        $restrictoptions[64] = '64%';
        $restrictoptions[63] = '63%';
        $restrictoptions[62] = '62%';
        $restrictoptions[61] = '61%';
        $restrictoptions[60] = '60%';
        $restrictoptions[59] = '59%';
        $restrictoptions[58] = '58%';
        $restrictoptions[57] = '57%';
        $restrictoptions[56] = '56%';
        $restrictoptions[55] = '55%';
        $restrictoptions[54] = '54%';
        $restrictoptions[53] = '53%';
        $restrictoptions[52] = '52%';
        $restrictoptions[51] = '51%';
		$restrictoptions[50] = '50%';
        $restrictoptions[49] = '49%';
        $restrictoptions[48] = '48%';
        $restrictoptions[47] = '47%';
        $restrictoptions[46] = '46%';
        $restrictoptions[45] = '45%';
        $restrictoptions[44] = '44%';
        $restrictoptions[43] = '43%';
        $restrictoptions[42] = '42%';
        $restrictoptions[41] = '41%';
        $restrictoptions[40] = '40%';
        $restrictoptions[39] = '39%';
        $restrictoptions[38] = '38%';
        $restrictoptions[37] = '37%';
        $restrictoptions[36] = '36%';
        $restrictoptions[35] = '35%';
        $restrictoptions[34] = '34%';
        $restrictoptions[33] = '33%';
        $restrictoptions[32] = '32%';
		$restrictoptions[31] = '31%';
        $restrictoptions[30] = '30%';
        $restrictoptions[29] = '29%';
        $restrictoptions[28] = '28%';
        $restrictoptions[27] = '27%';
        $restrictoptions[26] = '26%';
        $restrictoptions[25] = '25%';
        $restrictoptions[24] = '24%';
        $restrictoptions[23] = '23%';
        $restrictoptions[22] = '22%';
		$restrictoptions[21] = '21%';
		$restrictoptions[20] = '20%';
        $restrictoptions[19] = '19%';
        $restrictoptions[18] = '18%';
        $restrictoptions[17] = '17%';
        $restrictoptions[16] = '16%';
        $restrictoptions[15] = '15%';
        $restrictoptions[14] = '14%';
        $restrictoptions[13] = '13%';
        $restrictoptions[12] = '12%';
		$restrictoptions[11] = '11%';
        $restrictoptions[10]= '10%';
        $restrictoptions[9] = '9%';
        $restrictoptions[8] = '8%';
        $restrictoptions[7] = '7%';
        $restrictoptions[6] = '6%';
        $restrictoptions[5] = '5%';
        $restrictoptions[4] = '4%';
        $restrictoptions[3] = '3%';
        $restrictoptions[2] = '2%';
        $restrictoptions[1] = '1%';
		$mform->addElement('select', 'requiredgrade', get_string('requiredgrade', 'certificate'), $restrictoptions);
        $mform->setHelpButton('requiredgrade', array('requiredgrade', get_string('requiredgrade', 'certificate'), 'certificate'));
		
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'textoptions', get_string('textoptions', 'certificate'));		

        $dateoptions = array( 0 => get_string('no'), 1 => get_string('receiveddate', 'certificate'), 2 => get_string('courseenddate', 'certificate'));
        $mform->addElement('select', 'printdate', get_string('printdate', 'certificate'), $dateoptions);
        $mform->setDefault('printdate', 0);
	    $mform->setHelpButton('printdate', array('printdate', get_string('datehelp', 'certificate'), 'certificate'));
 
        $dateformatoptions = array( 1 => 'January 1, 2000', 2 => 'January 1st, 2000', 3 => '1 January 2000', 4 => 'January 2000');
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
		$this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // buttons
        $this->add_action_buttons();

	}



}
?>