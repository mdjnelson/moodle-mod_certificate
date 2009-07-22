<?php //$Id: version.php,v 3.1.9.3
    //This php script contains all the stuff to backup/restore

    //This function executes all the restore procedure about this mod
    function certificate_restore_mods($mod,$restore) {

        global $CFG, $DB;

        $status = true;

        //Get record from backup_ids
        $data = backup_getid($restore->backup_unique_code,$mod->modtype,$mod->id);

        if ($data) {
            //Now get completed xmlized object
            $info = $data->info;
            //traverse_xmlize($info);                                                               //Debug
            //print_object ($GLOBALS['traverse_array']);                                            //Debug
            //$GLOBALS['traverse_array']="";                                                        //Debug

            //Now, build the certificate record structure
            $certificate->course = $restore->course_id;
            $certificate->name = backup_todb($info['MOD']['#']['NAME']['0']['#']);
            $certificate->intro = backup_todb($info['MOD']['#']['INTRO']['0']['#']);
            $certificate->introformat = backup_todb($info['MOD']['#']['INTROFORMAT']['0']['#']);
            $certificate->emailteachers = backup_todb($info['MOD']['#']['EMAILTEACHERS']['0']['#']);
            $certificate->emailothers = backup_todb($info['MOD']['#']['EMAILOTHERS']['0']['#']);
            $certificate->savecert = backup_todb($info['MOD']['#']['SAVECERT']['0']['#']);
            $certificate->reportcert = backup_todb($info['MOD']['#']['REPORTCERT']['0']['#']);
            $certificate->reissuecert = backup_todb($info['MOD']['#']['REISSUECERT']['0']['#']);
            $certificate->delivery = backup_todb($info['MOD']['#']['DELIVERY']['0']['#']);
            $certificate->certificatetype = backup_todb($info['MOD']['#']['TYPE']['0']['#']);
            $certificate->orientation = backup_todb($info['MOD']['#']['ORIENTATION']['0']['#']);
            $certificate->reissuecert = backup_todb($info['MOD']['#']['REISSUECERT']['0']['#']);
            $certificate->borderstyle = backup_todb($info['MOD']['#']['BORDERSTYLE']['0']['#']);
            $certificate->bordercolor = backup_todb($info['MOD']['#']['BORDERCOLOR']['0']['#']);
            $certificate->printwmark = backup_todb($info['MOD']['#']['PRINTWMARK']['0']['#']);
            $certificate->printdate = backup_todb($info['MOD']['#']['PRINTDATE']['0']['#']);
            $certificate->datefmt = backup_todb($info['MOD']['#']['DATEFMT']['0']['#']);
            $certificate->printhours = backup_todb($info['MOD']['#']['PRINTHOURS']['0']['#']);
            $certificate->printnumber = backup_todb($info['MOD']['#']['PRINTNUMBER']['0']['#']);
            $certificate->printgrade = backup_todb($info['MOD']['#']['PRINTGRADE']['0']['#']);
            $certificate->gradefmt = backup_todb($info['MOD']['#']['GRADEFMT']['0']['#']);
            $certificate->printoutcome = backup_todb($info['MOD']['#']['PRINTOUTCOME']['0']['#']);
            $certificate->lockgrade = backup_todb($info['MOD']['#']['LOCKGRADE']['0']['#']);
            $certificate->requiredgrade = backup_todb($info['MOD']['#']['REQUIREDGRADE']['0']['#']);
            $certificate->printteacher = backup_todb($info['MOD']['#']['PRINTTEACHER']['0']['#']);
            $certificate->customtext = backup_todb($info['MOD']['#']['CUSTOMTEXT']['0']['#']);
            $certificate->printsignature = backup_todb($info['MOD']['#']['PRINTSIGNATURE']['0']['#']);
            $certificate->printseal = backup_todb($info['MOD']['#']['PRINTSEAL']['0']['#']);
            $certificate->printteacher = backup_todb($info['MOD']['#']['PRINTTEACHER']['0']['#']);
            $certificate->printsignature = backup_todb($info['MOD']['#']['PRINTSIGNATURE']['0']['#']);
            $certificate->printseal = backup_todb($info['MOD']['#']['PRINTSEAL']['0']['#']);
            $certificate->timemodified = backup_todb($info['MOD']['#']['TIMEMODIFIED']['0']['#']);

              //The structure is equal to the db, so insert the assignment
            $newid = $DB->insert_record (array("certificate"=>$certificate));

            //Do some output
            if (!defined('RESTORE_SILENTLY')) {
                echo "<li>".get_string('modulename','certificate')." \"".format_string(stripslashes($certificate->name),true)."\"</li>";
            }

            if ($newid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,$mod->modtype,
                             $mod->id, $newid);
                //Now check if want to restore user data and do it.
                if (restore_userdata_selected($restore,'certificate',$mod->id)) {
                    //Restore assignmet_submissions
                    $status = certificate_issues_restore_mods($mod->id, $newid,$info,$restore) && $status;
                }
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        return $status;
    }

//This function restores the certificate_issues
function certificate_issues_restore_mods($old_certificate_id, $new_certificate_id,$info,$restore) {

         global $CFG, $DB;

        $status = true;

        //Get the issued array
        $issues = $info['MOD']['#']['ISSUES']['0']['#']['ISSUE'];

        //Iterate over issueds
        for($i = 0; $i < sizeof($issues); $i++) {
            $iss_info = $issues[$i];
            //traverse_xmlize($view_info);                                                                 //Debug
            //print_object ($GLOBALS['traverse_array']);                                                  //Debug
            //$GLOBALS['traverse_array']="";                                                              //Debug

            //We'll need this later!!
            $oldid = backup_todb($iss_info['#']['ID']['0']['#']);
            $olduserid = backup_todb($iss_info['#']['USERID']['0']['#']);

            //Now, build the certificate_issues record structure
            $issue->certificateid = $new_certificate_id;
            $issue->userid = backup_todb($iss_info['#']['USERID']['0']['#']);
            $issue->timecreated = backup_todb($iss_info['#']['TIMECREATED']['0']['#']);
            $issue->studentname = backup_todb($iss_info['#']['STUDENTNAME']['0']['#']);
            $issue->code = backup_todb($iss_info['#']['CODE']['0']['#']);
            $issue->classname = backup_todb($iss_info['#']['CLASSNAME']['0']['#']);
            $issue->certdate = backup_todb($iss_info['#']['CERTDATE']['0']['#']);
            $issue->reportgrade = backup_todb($iss_info['#']['REPORTGRADE']['0']['#']);
            $issue->mailed = backup_todb($iss_info['#']['MAILED']['0']['#']);

 //We have to recode the userid field
            $user = backup_getid($restore->backup_unique_code,"user",$issue->userid);
            if ($user) {
                $issue->userid = $user->new_id;
            }

//The structure is equal to the db, so insert the certificate_issue
            $newid = $DB->insert_record (array("certificate_issues"=>$issue));

 //Do some output
        if (($i+1) % 50 == 0) {
            if (!defined('RESTORE_SILENTLY')) {
                echo ".";
                if (($i+1) % 1000 == 0) {
                    echo "<br />";
                }
            }
            backup_flush(300);
        }

  if ($newid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,"certificate_issues",$oldid,
                             $newid);

                //Now copy moddata associated files
                $status = certificate_restore_files ($old_certificate_id, $new_certificate_id,
                                                    $olduserid, $issue->userid, $restore);

            } else {
                $status = false;
            }
        }

        return $status;
    }




 //This function copies the certificate related info from backup temp dir to course moddata folder,
    //creating it if needed and recoding everything (certificate id and user id)
    function certificate_restore_files ($old_cert_id, $new_cert_id, $old_user_id, $new_user_id, $restore) {
        global $course, $certificate, $CFG;

        $status = true;
        $todo = false;
        $moddata_path = "";
        $certificate_path = "";
        $temp_path = "";

        //First, we check to "course_id" exists and create is as necessary
        //in CFG->dataroot
        $dest_dir = $CFG->dataroot."/".$restore->course_id;
        $status = check_dir_exists($dest_dir,true);

        //Now, locate course's moddata directory
        $moddata_path = $CFG->dataroot."/".$restore->course_id."/".$CFG->moddata;

        //Check it exists and create it
        $status = check_dir_exists($moddata_path,true);

        //Now, locate certificate directory
        if ($status) {
            $certificate_path = $moddata_path."/certificate";
            //Check it exists and create it
            $status = check_dir_exists($certificate_path,true);
        }

        //Now locate the temp dir we are going to restore
        if ($status) {
            $temp_path = $CFG->dataroot."/temp/backup/".$restore->backup_unique_code.
                         "/moddata/certificate/".$old_cert_id."/".$old_user_id;
            //Check it exists
            $todo = check_dir_exists($temp_path);
        }

        //If todo, we create the neccesary dirs in course moddata/certificate
        if ($status and $todo) {
            //First this certificate id
            $this_certificate_path = $certificate_path."/".$new_cert_id;
            $status = check_dir_exists($this_certificate_path,true);
            //Now this user id
            $user_certificate_path = $this_certificate_path."/".$new_user_id;
            $status = check_dir_exists($user_certificate_path,true);
            //And now, copy temp_path to user_certificate_path
            $status = @backup_copy_file($temp_path, $user_certificate_path);
        }

        return $status;
    }
 //This function returns a log record with all the necessay transformations
    //done. It's used by restore_log_module() to restore modules log.
    function certificate_restore_logs($restore,$log) {

        $status = false;

        //Depending of the action, we recode different things
        switch ($log->action) {
        case "add":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "update":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "view":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "received":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                     $log->url = "report.php?a=".$mod->new_id;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
           case "view all":
            $log->url = "index.php?id=".$log->course;
            $status = true;
            break;
        case "view report":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "report.php?id=".$mod->new_id;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        default:
            if (!defined('RESTORE_SILENTLY')) {
                echo "action (".$log->module."-".$log->action.") unknown. Not restored<br />";                 //Debug
            }
            break;
        }

        if ($status) {
            $status = $log;
        }
        return $status;
    }
?>