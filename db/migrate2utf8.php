<?php // $Id$
function migrate2utf8_certificate_name($recordid){
    global $CFG, $globallang;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }
    if (!$certificate = get_record('certificate', 'id', $recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if ($globallang) {
        $fromenc = $globallang;
    } else {
        $sitelang   = $CFG->lang;
        $courselang = get_course_lang($certificate->course);  //Non existing!
        $userlang   = get_main_teacher_lang($certificate->course); //N.E.!!

        $fromenc = get_original_encoding($sitelang, $courselang, $userlang);
    }
/// We are going to use textlib facilities
    
/// Convert the text
    if (($fromenc != 'utf-8') && ($fromenc != 'UTF-8')) {
        $result = utfconvert($certificate->name, $fromenc);

        $newcertificate = new object;
        $newcertificate->id = $recordid;
        $newcertificate->name = $result;
        migrate2utf8_update_record('certificate',$newcertificate);
    }



/// And finally, just return the converted field
    return $result;
}

?>
