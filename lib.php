<?PHP  // Created by Hugo Salgado <hsalgado@vulcano.cl>

/// Library of functions and constants for module certificate


function certificate_add_instance($certificate) {
/// Given an object containing all the necessary data, 
/// (defined by the form in mod.html) this function 
/// will create a new instance and return the id number 
/// of the new instance.

    $certificate->timemodified = time();

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

    return $return;
}

function certificate_user_complete($course, $user, $mod, $certificate) {
/// Print a detailed representation of what a  user has done with 
/// a given particular instance of this module, for user activity reports.

    return true;
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


?>
