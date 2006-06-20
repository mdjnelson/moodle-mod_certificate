<?PHP 
//require_once("../../config.php");
require_once($CFG->dirroot.'/course/lib.php');
//require_once($CFG->libdir.'/locklib.php');

$certificate_CONSTANT = 7;     /// for example


function certificate_add_instance($certificate) {
	/// Given an object containing all the necessary data,
	/// (defined by the form in mod.html) this function
	/// will create a new instance and return the id number
	/// of the new instance.

	$certificate->timemodified = time();
 	$certificate->id = '';
	# May have to add extra stuff in here #

	return insert_record("certificate", $certificate);
}


function certificate_update_instance($certificate) {
	/// Given an object containing all the necessary data,
	/// (defined by the form in mod.html) this function
	/// will update an existing instance with new data.

	$certificate->timemodified = time();
	$certificate->id = $certificate->instance;

	# May have to add extra stuff in here #

	return update_record("certificate", $certificate);
}


function certificate_delete_instance($id) {
	/// Given an ID of an instance of this module,
	/// this function will permanently delete the instance
	/// and any data that depends on it.

	if (! $certificate = get_record("certificate", "id", "$id")) {
		return false;
	}

	$result = true;

	# Delete any dependent records here #

	if (! delete_records("certificate", "id", "$certificate->id")) {
		$result = false;
	}

	return $result;
}

function certificate_user_outline($course, $user, $mod, $certificate) {
	/// Return a small object with summary information about what a
	/// user has done with a given particular instance of this module
	/// Used for user activity reports.
	/// $return->time = the time they did it
	/// $return->info = a short text description

	if ($logs = get_records_select("log", "userid='$user->id' AND module='certificate' AND action='view' AND info='$certificate->id'", "time ASC")) {

	$numviews = count($logs);
	$lastlog = array_pop($logs);

	$result->info = get_string("numviews", "", $numviews);
	$result->time = $lastlog->time;

	return $result;
}
return NULL;

}

function certificate_user_complete($course, $user, $mod, $certificate) {
	/// Print a detailed representation of what a  user has done with
	/// a given particular instance of this module, for user activity reports.

	global $CFG;

	if ($logs = get_records_select("log", "userid='$user->id' AND module='certificate' AND action='view' AND info='$certificate->id'", "time ASC")) {
	$numviews = count($logs);
	$lastlog = array_pop($logs);

	$strmostrecently = get_string("mostrecently");
	$strnumviews = get_string("numviews", "", $numviews);

	echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

} else {
	print_string("neverseen", "resource");
}
}

function certificate_print_recent_activity($course, $isteacher, $timestart) {
	/// Given a course and a time, this module should find recent activity
	/// that has occurred in certificate activities and print it out.
	/// Return true if there was output, or false is there was none.

	global $CFG;

	return false;  //  True if anything was printed, otherwise false
}

function certificate_cron () {
	/// Function to be run periodically according to the moodle cron
	/// This function searches for things that need to be done, such
	/// as sending out mail, toggling flags etc ...

	global $CFG;

	return true;
}

function certificate_grades($certificateid) {
	/// Must return an array of grades for a given instance of this module,
	/// indexed by user.  It also returns a maximum allowed grade.
	///
	///    $return->grades = array of grades;
	///    $return->maxgrade = maximum allowed grade;
	///
	///    return $return;

	return NULL;
}

function certificate_get_participants($certificateid) {
	//Must return an array of user records (all data) who are participants
	//for a given instance of certificate. Must include every user involved
	//in the instance, independient of his role (student, teacher, admin...)
	//See other modules as example.

	return false;
}

function certificate_scale_used ($certificateid,$scaleid) {
	//This function returns if a scale is being used by one certificate
	//it it has support for grading and scales. Commented code should be
	//modified if necessary. See forum, glossary or journal modules
	//as reference.

	$return = false;

	//$rec = get_record("certificate","id","$certificateid","scale","-$scaleid");
	//
	//if (!empty($rec)  && !empty($scaleid)) {
	//    $return = true;
	//}

	return $return;
}

function md5_encrypt($plain_text, $password, $iv_len = 16)
{
	$plain_text .= "\x13";
	$n = strlen($plain_text);
	#if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
	$i = 0;
	$enc_text = get_rnd_iv($iv_len);
	$iv = substr($password ^ $enc_text, 0, 512);
	while ($i < $n) {
		$block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
		$enc_text .= $block;
		$iv = substr($block . $iv, 0, 512) ^ $password;
		$i += 16;
	}
	return base64_encode($enc_text);
}

function md5_decrypt($enc_text, $password, $iv_len = 16)
{
	$enc_text = base64_decode($enc_text);
	$n = strlen($enc_text);
	$i = $iv_len;
	$plain_text = '';
	$iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
	while ($i < $n) {
		$block = substr($enc_text, $i, 16);
		$plain_text .= $block ^ pack('H*', md5($iv));
		$iv = substr($block . $iv, 0, 512) ^ $password;
		$i += 16;
	}
	return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}

function get_rnd_iv($iv_len) {
	$iv = '';
	while ($iv_len-- > 0) {
		$iv .= chr(mt_rand() & 0xff);
	}
	return $iv;
}

function get_group_teachers_noedit($courseid, $groupid) {
	/// Returns a list of all the teachers who can access a group
	if ($teachers = get_course_teachers($courseid)) {
		foreach ($teachers as $key => $teacher) {
			if ($teacher->editall) {             // These can access anything
			continue;
			}
			unset($teachers[$key]);
		}
	}
	return $teachers;
}

/// Search through all the modules, pulling out grade data

function cert_get_grades() {
	global $course, $CFG;

	/// Collect modules data
	get_all_mods($course->id, $mods, $modnames, $modnamesplural, $modnamesused);

	$cert_grade = array();
	$sections = get_all_sections($course->id); // Sort everything the same as the course
	for ($i=0; $i<=$course->numsections; $i++) {
		// should always be true
		if (isset($sections[$i])) {
			$section = $sections[$i];
			if ($section->sequence) {
				switch ($course->format) {
					case "topics":
					$sectionlabel = get_string("topic");
					break;
					case "weeks":
					$sectionlabel = get_string("week");
					break;
					default:
					$sectionlabel = get_string("section");
				}

				$sectionmods = explode(",", $section->sequence);
				foreach ($sectionmods as $sectionmod) {
					$mod = $mods[$sectionmod];
					// no labels
					if ( $mod->modname != "label") {
						$instance = get_record("$mod->modname", "id", "$mod->instance");
						$libfile = "$CFG->dirroot/mod/$mod->modname/lib.php";
						if (file_exists($libfile)) {
							require_once($libfile);
							$gradefunction = $mod->modname."_grades";
							// Modules with grade function (excluding forums)
							if (function_exists($gradefunction) and $mod->modname != "forum") {
								// Modules with grading set
								if ($modgrades = $gradefunction($mod->instance) and !empty($modgrades->maxgrade)) {
									//if (!($mod->id == $id)) { // A module can't be a lock for itself
									//echo "<td>".get_String("requiredgrade", "lock").": <select name=\"$mod->id\" size=\"1\">"
									$cert_grade[$mod->id] = $sectionlabel.' '.$section->section.' : '.$instance->name;
									//}
								}
							}
							else { //Modules without a grade set but with a grade function
							}

						} // libfile
					} // no labels
				} //close foreach
			} // if section
		} // isset section
	}
	if(isset($cert_grade)) {
		$gradeoptions['0'] = get_string("dont_print_grade", "certificate");
		$gradeoptions['1'] = get_string("course_grade", "certificate");
		foreach($cert_grade as $key => $value) {
			$gradeoptions[$key] = $value;
		}
	} else { $gradeoptions['0'] = get_string("no_grades", "certificate"); }
	return ($gradeoptions);
}

function cert_get_grade($course, $moduleid) {
	global $USER, $CFG;

	$cm = get_record("course_modules", "id", $moduleid);

	//print_r($cm);
	$module = get_record("modules", "id", $cm->module);

	//var_dump($module);
	$libfile = $CFG->dirroot."/mod/".$module->name."/lib.php";
	if (file_exists($libfile)) {
		require_once($libfile);
		$gradefunction = $module->name."_grades";
		if (function_exists($gradefunction)) {   // Modules with grade function
		if ($modgrades = $gradefunction($cm->instance)) {
			//var_dump($USER->id);
			//var_dump($modgrades);
			//var_dump($modgrades->grades[$USER->id]);
			$modinfo->name = get_field($module->name, 'name', 'id', $cm->instance);
			$modinfo->grade = $modgrades->grades[$USER->id];
            return $modinfo;
		}
		}
	}
	return false;
}

/**
* cert_printtext sends text to output given the following params.
*
* @param int $x horizontal position in pixels
* @param int $y vertical position in pixels
* @param char $align L=left, C=center, R=right
* @param string $font any available font in font directory
*        Courier  Helvetica Times Symbol  ZapfDingbats 
* @param char $style ''=normal, B=bold, I=italic, U=underline
* @param int $size font size in points
* @param string $text the text to print
* @return null
*/
function cert_printtext( $x, $y, $align, $font, $style, $size, $text) {
	global $pdf;
	$pdf->setFont("$font", "$style", $size);
	$pdf->SetXY( $x, $y);
	$pdf->Cell( 500, 0, "$text", 0, 1, "$align");
}

function set_color($color) {
	global $pdf;

	switch($color) {
		case get_string('border_black', 'certificate'):
		$pdf->SetFillColor( 0, 0, 0);
		break;
		case get_string('border_brown', 'certificate'):
		$pdf->SetFillColor(153, 102, 51 );
		break;
		case get_string('border_blue', 'certificate'):
		$pdf->SetFillColor( 0, 51, 204);
		break;
		case get_string('border_green', 'certificate'):
		$pdf->SetFillColor( 0, 180, 0);
		break;
	}
}

function draw_frame($color, $orientation) {
	global $pdf;

	switch ($orientation) {
		case 'L':
		// create box border
		set_color($color);
		$pdf->Rect( 20, 20, 790, 530, 'F');
		$pdf->SetFillColor( 255, 255, 255);
		$pdf->Rect( 26, 26, 778, 518, 'F');
		set_color($color);  // middle
		$pdf->Rect( 35, 35, 760, 500, 'F');
		$pdf->SetFillColor( 255, 255, 255);
		$pdf->Rect( 36, 36, 758, 498, 'F');

		set_color($color);  // inside
		$pdf->Rect( 46, 46, 738, 478, 'F');

		$pdf->SetFillColor( 255, 255, 255);  // whiten inside are
		$pdf->Rect( 50, 50, 730, 470, 'F');
		$pdf->SetFillColor( 0, 0, 0);  // reset to black
		break;
		case 'P':
		// create box border
		set_color($color);
		$pdf->Rect( 20, 20, 560, 800, 'F');
		$pdf->SetFillColor( 255, 255, 255);
		$pdf->Rect( 26, 26, 548, 788, 'F');
		set_color($color);  // middle
		$pdf->Rect( 35, 35, 530, 770, 'F');
		$pdf->SetFillColor( 255, 255, 255);
		$pdf->Rect( 36, 36, 528, 768, 'F');

		set_color($color);  // inside
		$pdf->Rect( 46, 46, 508, 748, 'F');

		$pdf->SetFillColor( 255, 255, 255);  // whiten inside are
		$pdf->Rect( 50, 50, 500, 740, 'F');
		$pdf->SetFillColor( 0, 0, 0);  // reset to black
		break;
	}
}

function print_border($border, $color, $orientation) {
	global $CFG, $pdf;

	switch($border) {
		case '0':
		break;
		case '1':
		draw_frame($color, $orientation);
		break;
		default:
		if(file_exists("$CFG->dirroot/mod/certificate/pix/borders/$border-$color.png")) {
			$pdf->Image( "$CFG->dirroot/mod/certificate/pix/borders/$border-$color.png", 10, 10, 820, 580);
		}
		break;
	}
}

function print_watermark($wmark, $orientation) {
	global $CFG, $pdf;

	switch($wmark) {
		case '0':
		break;
		default:
		switch ($orientation) {
			case 'L':
			if(file_exists("$CFG->dirroot/mod/certificate/pix/watermarks/$wmark")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/watermarks/$wmark", 100, 100, 620, 420);
			}
			break;
			case 'P':
			if(file_exists("$CFG->dirroot/mod/certificate/pix/watermarks/$wmark")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/watermarks/$wmark", 40, 40, 500, 420);
			}
			break;
		}
		break;
	}
}

function print_signature($sig, $orientation) {
	global $CFG, $pdf;

	switch ($orientation) {
		case 'L':
		switch($sig) {
			case '0':
			break;
			case '1':
			$pdf->SetFillColor( 0, 0, 0);
			$pdf->Rect( 120, 440, 160, 1, 'F');
			break;
			default:
			if(file_exists("$CFG->dirroot/mod/certificate/pix/signatures/$sig")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/signatures/$sig", 140, 415);
			}
			$pdf->SetFillColor( 0, 0, 0);
			$pdf->Rect( 120, 440, 160, 1, 'F');
			break;
		}
		break;
		case 'P':
		switch($sig) {
			case '0':
			break;
			case '1':
			$pdf->SetFillColor( 0, 0, 0);
			$pdf->Rect( 80, 740, 160, 1, 'F');
			break;
			default:
			if(file_exists("$CFG->dirroot/mod/certificate/pix/signatures/$sig")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/signatures/$sig", 100, 715);
			}
			$pdf->SetFillColor( 0, 0, 0);
			$pdf->Rect( 80, 740, 160, 1, 'F');
			break;
		}
		break;
	}
}

function print_seal($seal, $orientation) {
	global $CFG, $pdf;

	switch($seal) {
		case '0':
		break;
		default:
		switch ($orientation) {
			case 'L':
			if(file_exists("$CFG->dirroot/mod/certificate/pix/seals/$seal")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/seals/$seal", 620, 390, 80, 80);
			}
			break;
			case 'P':
			if(file_exists("$CFG->dirroot/mod/certificate/pix/seals/$seal")) {
				$pdf->Image( "$CFG->dirroot/mod/certificate/pix/seals/$seal", 450, 680, 80, 80);
			}
			break;
		}
		break;
	}
}

function print_teachers($teachers) {
	global $cert, $course;
	$i = 0 ;
	
	if($cert->print_teacher) {
		foreach ($teachers as $teacher) {
			$i++;
			if($i <5) {
				$fullname = fullname($teacher, isteacher($course->id));
				cert_printtext(150, 440+($i *12) , 'L', 'Times', '', 12, $fullname);
			}
		}
	}
}

function print_code($code) {
	global $cert;

	if($cert->print_number > 0) {
		cert_printtext(170, 475, 'C', 'Helvetica', '', 10, $code);
	}
}

function generate_code() {
	return randLetter() . randLetter() . randLetter() . randLetter() . randLetter() . randLetter();
}

function randLetter() {
    $int = rand(0,25);
    $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}

function check_code( $user, $cm) {
	return 0;
}

function check_cert_exists($course, $user) {
 global $course;
 if (record_exists("certificate_viewed", "courseid", $course->id, "userid", $user->id)) {
  $CERT = get_record("certificate_viewed","courseid", $course->id, "userid", $user->id);
 } else {
  $CODE = generate_code();
  $view_time = time();
  $studentname = addslashes($user->firstname . " " . $user->lastname);
  insert_record("certificate_viewed", array("courseid" => $course->id, "userid" => $user->id, "studentname" => $studentname, "code" => $CODE, "cert_date" => $view_time, "classname" => $course->fullname), false);

  $CERT = get_record("certificate_viewed","courseid", $course->id, "userid", $user->id);  
 }
 return $CERT;
}
function get_course_grade($id){
	global $course, $CFG, $USER;
    require_variable($id);              // course id

    if (! $course = get_record("course", "id", $id)) {
        error("Course ID was incorrect");
    }
    
    if (!$course->showgrades) {
        error("Grades are not available for students in this course");
    }

    require_login($course->id);
//var_dump($course);
    $strgrades = get_string("grades");
    $strgrade = get_string("grade");
    $strmax = get_string("maximumshort");
    $stractivityreport = get_string("activityreport");


/// Get a list of all students

    $columnhtml = array();  // Accumulate column html in this array.
    $grades = array();      // Collect all grades in this array
    $maxgrades = array();   // Collect all max grades in this array
    $totalgrade = 0;
    $totalmaxgrade = 0;


/// Collect modules data
$test=get_all_mods($course->id, $mods, $modnames, $modnamesplural, $modnamesused);

/// Search through all the modules, pulling out grade data
    $sections = get_all_sections($course->id); // Sort everything the same as the course
    for ($i=0; $i<=$course->numsections; $i++) {
        if (isset($sections[$i])) {   // should always be true
            $section = $sections[$i];
            if (!empty($section->sequence)) {
                $sectionmods = explode(",", $section->sequence);
                foreach ($sectionmods as $sectionmod) {
                    $mod = $mods[$sectionmod];
					
                    if ($mod->visible) {
					
                        $instance = get_record("$mod->modname", "id", "$mod->instance");
                        $libfile = "$CFG->dirroot/mod/$mod->modname/lib.php";
						
                        if (file_exists($libfile)) {
                            require_once($libfile);
							//echo $mod->modname;
                            $gradefunction = $mod->modname."_grades";
							
                            if (function_exists($gradefunction)) {   // Skip modules without grade function
                                if ($modgrades = $gradefunction($mod->instance)) {

                                    
        
                                    if (empty($modgrades->grades[$USER->id])) {
                                        $grades[]  = "";
                                    } else {
                                        $grades[]  = $modgrades->grades[$USER->id];
                                        $totalgrade += (float)$modgrades->grades[$USER->id];
                                    }
    
                                    if (empty($modgrades->maxgrade)) {
                                        $maxgrades[] = "";
                                    } else {
                                        $maxgrades[]    = $modgrades->maxgrade;
                                        $totalmaxgrade += $modgrades->maxgrade;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } 


/// OK, we have all the data, now present it to the user

   
if ($totalmaxgrade == 0) {
    $percentage = 1;
} else {
    $percentage = round(($totalgrade*100/$totalmaxgrade),2);
} 
   
return $percentage;
    
}

?>
