<?php //$Id: version.php,v 3.1.0
    //This php script contains all the stuff to backup/restore
    //-----------------------------------------------------------

    function certificate_backup_mods($bf,$preferences) {

        global $CFG;

        $status = true;
                
        //Iterate over certificate table
        
 if ($certificates = get_records('certificate','course',$preferences->backup_course,"id")) {
        foreach ($certificates as $certificate) {
           if (function_exists('backup_mod_selected')) {
                    // Moodle 1.6
                    $backup_mod_selected = backup_mod_selected($preferences, 'certificate', $certificate->id);
            } else {
                    // Moodle 1.5
                $backup_mod_selected = true;
            }
            if ($backup_mod_selected) {
                $status = data_backup_one_mod($bf,$preferences,$data);
                // backup files happens in backup_one_mod now too.
            }
        }
    }
    return $status;
}

        function certificate_backup_one_mod($bf,$preferences,$certificate) {

        global $CFG;
    
        if (is_numeric($certificate)) {
            $certificate = get_record('certificate','id',$certificate);
        }
    
        $status = true;

                //Start mod
                fwrite ($bf,start_tag("MOD",3,true));
                //Print certificate data
                fwrite ($bf,full_tag("ID",4,false,$certificate->id));
                fwrite ($bf,full_tag("MODTYPE",4,false,"certificate"));
                fwrite ($bf,full_tag("NAME",4,false,$certificate->name));
                fwrite ($bf,full_tag("EMAILTEACHERS",4,false,$certificate->emailteachers));
                fwrite ($bf,full_tag("SAVECERT",4,false,$certificate->savecert));
                fwrite ($bf,full_tag("DELIVERY",4,false,$certificate->delivery));
                fwrite ($bf,full_tag("TYPE",4,false,$certificate->certificatetype));
                fwrite ($bf,full_tag("BORDERSTYLE",4,false,$certificate->borderstyle));
                fwrite ($bf,full_tag("BORDERCOLOR",4,false,$certificate->bordercolor));
                fwrite ($bf,full_tag("PRINTWMARK",4,false,$certificate->printwmark));
                fwrite ($bf,full_tag("PRINTDATE",4,false,$certificate->printdate));
                fwrite ($bf,full_tag("DATEFMT",4,false,$certificate->datefmt));
                fwrite ($bf,full_tag("PRINTNUMBER",4,false,$certificate->printnumber));
                fwrite ($bf,full_tag("PRINTGRADE",4,false,$certificate->printgrade));
                fwrite ($bf,full_tag("PRINTTEACHER",4,false,$certificate->printteacher));
                fwrite ($bf,full_tag("PRINTSIGNATURE",4,false,$certificate->printsignature));
                fwrite ($bf,full_tag("PRINTSEAL",4,false,$certificate->printseal));
                fwrite ($bf,full_tag("TIMEMODIFIED",4,false,$certificate->timemodified));
       //if we've selected to backup users info, then execute backup_certificate_issues and
        //backup_certificate_files_instance
        if (backup_userdata_selected($preferences,'certificate',$certificate->id)) {
            $status = backup_certificate_issues($bf,$preferences,$certificate->id);
            if ($status) {
                $status = backup_certificate_file_instance($bf,$preferences,$certificate->id);
            }
        }
        //End mod
       fwrite ($bf,end_tag("MOD",3,true));
       return $status;
    }

 //Backup certificate_issues contents (executed from certificate_backup_mods)
    function backup_certificate_issues ($bf,$preferences,$certificateid) {

        global $CFG;
        $status = true;

        $certificate_issues = get_records("certificate_issues","certificateid",$certificateid);
        //If there is issues
        if ($certificate_issues) {
            //Write start tag
            $status =fwrite ($bf,start_tag("ISSUES",4,true));
            //Iterate over each issue
            foreach ($certificate_issues as $cert_iss) {
                //Start issues
                $status =fwrite ($bf,start_tag("ISSUE",5,true));
                //Print viewed contents
                fwrite ($bf,full_tag("ID",6,false,$cert_iss->id));
                fwrite ($bf,full_tag("CERTIFICATEID",6,false,$cert_iss->certificateid)); 
                fwrite ($bf,full_tag("USERID",6,false,$cert_iss->userid));       
                fwrite ($bf,full_tag("TIMECREATED",6,false,$cert_iss->timecreated));        
                fwrite ($bf,full_tag("STUDENTNAME",6,false,$cert_iss->studentname));
                fwrite ($bf,full_tag("CODE",6,false,$cert_iss->code));       
                fwrite ($bf,full_tag("CLASSNAME",6,false,$cert_iss->classname));       
                fwrite ($bf,full_tag("CERTDATE",6,false,$cert_iss->certdate));       
                fwrite ($bf,full_tag("MAILED",6,false,$cert_iss->mailed));       
                
                //End viewed
                $status =fwrite ($bf,end_tag("ISSUE",5,true));
            }
            //Write end tag
            $status =fwrite ($bf,end_tag("ISSUES",4,true));
        }
        return $status;
    }
//Backup certificate files because we've selected to backup user info
    //and files are user info's level
 function backup_certificate_files($bf,$preferences) {

        global $CFG;
       
        $status = true;

        //First we check to moddata exists and create it as necessary
        //in temp/backup/$backup_code  dir
        $status = check_and_create_moddata_dir($preferences->backup_unique_code);
        //Now copy the certificate dir
        if ($status) {
            //Only if it exists !! Thanks to Daniel Miksik.
            if (is_dir($CFG->dataroot."/".$preferences->backup_course."/".$CFG->moddata."/certificate")) {
                $status = backup_copy_file($CFG->dataroot."/".$preferences->backup_course."/".$CFG->moddata."/certificate",
                                           $CFG->dataroot."/temp/backup/".$preferences->backup_unique_code."/moddata/certificate");
            }
        }

        return $status;

    } 
function backup_certificate_file_instance($bf,$preferences,$instanceid) {

        global $course, $certificate, $CFG;
       
        $status = true;

        //First we check to moddata exists and create it as necessary
        //in temp/backup/$backup_code  dir
        $status = check_and_create_moddata_dir($preferences->backup_unique_code);
        $status = check_dir_exists($CFG->dataroot."/temp/backup/".$preferences->backup_unique_code."/moddata/certificate/",true);
        //Now copy the certificate dir
        if ($status) {
            //Only if it exists !! Thanks to Daniel Miksik.
            if (is_dir($CFG->dataroot."/".$preferences->backup_course."/".$CFG->moddata."/certificate/".$instanceid)) {
                $status = backup_copy_file($CFG->dataroot."/".$preferences->backup_course."/".$CFG->moddata."/certificate/".$instanceid,
                                           $CFG->dataroot."/temp/backup/".$preferences->backup_unique_code."/moddata/certificate/".$instanceid);
            }
        }

        return $status;

    } 

        ////Return an array of info (name,value)
   function certificate_check_backup_mods($course,$user_data=false,$backup_unique_code,$instances=null) {
        if (!empty($instances) && is_array($instances) && count($instances)) {
            $info = array();
            foreach ($instances as $id => $instance) {
                $info += certificate_check_backup_mods_instances($instance,$backup_unique_code);
            }
            return $info;
        }
        //First the course data
        $info[0][0] = get_string('modulenameplural','certificate');
        if ($ids = certificate_ids ($course)) {
            $info[0][1] = count($ids);
        } else {
            $info[0][1] = 0;
        }

        //Now, if requested, the user_data
        if ($user_data) {
            $info[1][0] = get_string('receivedcerts','certificate');
            if ($ids = certificate_issues_ids_by_course ($course)) { 
                $info[1][1] = count($ids);
            } else {
                $info[1][1] = 0;
            }
        }
        return $info;
    }

 ////Return an array of info (name,value)
   function certificate_check_backup_mods_instances($instance,$backup_unique_code) {
        //First the course data
        $info[$instance->id.'0'][0] = '<b>'.$instance->name.'</b>';
        $info[$instance->id.'0'][1] = '';

      //Now, if requested, the user_data
        if (!empty($instance->userdata)) {
            $info[$instance->id.'1'][0] = get_string('receivedcerts','certificate');
            if ($ids = certificate_issues_ids_by_instance ($instance->id)) { 
                $info[$instance->id.'1'][1] = count($ids);
            } else {
                $info[$instance->id.'1'][1] = 0;
            }
        }
        return $info;
    }
    // INTERNAL FUNCTIONS. BASED IN THE MOD STRUCTURE

      //Returns an array of certificate ids
    function certificate_ids ($course) {

        global $CFG;

        return get_records_sql ("SELECT a.id, a.course
                                 FROM {$CFG->prefix}certificate a
                                 WHERE a.course = '$course'");
    }
   //Returns an array of certificate_issues id
    function certificate_issues_ids_by_course ($course) {

        global $CFG;

        return get_records_sql ("SELECT s.id , s.certificateid
                                 FROM {$CFG->prefix}certificate_issues s,
                                      {$CFG->prefix}certificate a
                                 WHERE a.course = '$course' AND
                                       s.certificateid = a.id");
    }
   //Returns an array of certificate_issues id
    function certificate_issues_ids_by_instance ($instanceid) {

        global $CFG;

        return get_records_sql ("SELECT s.id , s.certificateid
                                 FROM {$CFG->prefix}certificate_issues s
                                 WHERE s.certificateid = $instanceid");
    } 
?>