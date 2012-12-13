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
 * Certificate module core interaction API
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/grade/lib.php');
require_once($CFG->dirroot.'/grade/querylib.php');

/** The border image folder */
define('CERT_IMAGE_BORDER', 'borders');
/** The watermark image folder */
define('CERT_IMAGE_WATERMARK', 'watermarks');
/** The signature image folder */
define('CERT_IMAGE_SIGNATURE', 'signatures');
/** The seal image folder */
define('CERT_IMAGE_SEAL', 'seals');

define('CERT_PER_PAGE', 30);
define('CERT_MAX_PER_PAGE', 200);

/**
 * Add certificate instance.
 *
 * @param stdClass $certificate
 * @return int new certificate instance id
 */
function certificate_add_instance($certificate) {
    global $DB;

    $certificate->timecreated = time();
    $certificate->timemodified = $certificate->timecreated;

    if ($certificateid = $DB->insert_record('certificate', $certificate)) {
        $event = new stdClass;
        $event->name = $certificate->name;
        $event->description = '';
        $event->courseid = $certificate->course;
        $event->groupid = 0;
        $event->userid = 0;
        $event->eventtype = 'course';
        $event->modulename = 'certificate';
        $event->instance = $certificateid;

        add_event($event);
    }

    return $certificateid;
}

/**
 * Update certificate instance.
 *
 * @param stdClass $certificate
 * @return bool true
 */
function certificate_update_instance($certificate) {
    global $DB;

    // Update the certificate
    $certificate->timemodified = time();
    $certificate->id = $certificate->instance;
    $DB->update_record('certificate', $certificate);

    // Update the event if it exists, else create
    if ($event= $DB->get_record('event', array('modulename'=>'certificate', 'instance'=>$certificate->id))) {
        $event->name = $certificate->name;
        update_event($event);
    } else {
        $event = new stdClass;
        $event->name = $certificate->name;
        $event->description = '';
        $event->courseid = $certificate->course;
        $event->groupid = 0;
        $event->userid = 0;
        $event->modulename  = 'certificate';
        $event->instance = $certificate->id;
        add_event($event);
    }

    return true;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id
 * @return bool true if successful
 */
function certificate_delete_instance($id) {
    global $DB;

    // Ensure the certificate exists
    if (!$certificate = $DB->get_record('certificate', array('id' => $id))) {
        return false;
    }

    // Prepare file record object
    if (!$cm = get_coursemodule_from_instance('certificate', $id)) {
        return false;
    }

    $result = true;
    $DB->delete_records('certificate_issues', array('certificateid' => $id));
    if (!$DB->delete_records('certificate', array('id' => $id))) {
        $result = false;
    }

    // Delete any files associated with the certificate
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $fs = get_file_storage();
    $fs->delete_area_files($context->id);

    return $result;
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all posts from the specified certificate
 * and clean up any related data.
 *
 * Written by Jean-Michel Vedrine
 *
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function certificate_reset_userdata($data) {
    global $CFG, $DB;

    $componentstr = get_string('modulenameplural', 'certificate');
    $status = array();

    if (!empty($data->reset_certificate)) {
        $sql = "SELECT cert.id
                FROM {certificate} cert
                WHERE cert.course = :courseid";
        $DB->delete_records_select('certificate_issues', "certificateid IN ($sql)", array('courseid' => $data->courseid));
        $status[] = array('component' => $componentstr, 'item' => get_string('certificateremoved', 'certificate'), 'error' => false);
    }

    // Updating dates - shift may be negative too
    if ($data->timeshift) {
        shift_course_mod_dates('certificate', array('timeopen', 'timeclose'), $data->timeshift, $data->courseid);
        $status[] = array('component' => $componentstr, 'item' => get_string('datechanged'), 'error' => false);
    }

    return $status;
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the certificate.
 *
 * Written by Jean-Michel Vedrine
 *
 * @param $mform form passed by reference
 */
function certificate_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'certificateheader', get_string('modulenameplural', 'certificate'));
    $mform->addElement('advcheckbox', 'reset_certificate', get_string('deletissuedcertificates', 'certificate'));
}

/**
 * Course reset form defaults.
 *
 * Written by Jean-Michel Vedrine
 *
 * @param stdClass $course
 * @return array
 */
function certificate_reset_course_form_defaults($course) {
    return array('reset_certificate' => 1);
}

/**
 * Returns information about received certificate.
 * Used for user activity reports.
 *
 * @param stdClass $course
 * @param stdClass $user
 * @param stdClass $mod
 * @param stdClass $certificate
 * @return stdClass the user outline object
 */
function certificate_user_outline($course, $user, $mod, $certificate) {
    global $DB;

    $result = new stdClass;
    if ($issue = $DB->get_record('certificate_issues', array('certificateid' => $certificate->id, 'userid' => $user->id))) {
        $result->info = get_string('issued', 'certificate');
        $result->time = $issue->timecreated;
    } else {
        $result->info = get_string('notissued', 'certificate');
    }

    return $result;
}

/**
 * Returns information about received certificate.
 * Used for user activity reports.
 *
 * @param stdClass $course
 * @param stdClass $user
 * @param stdClass $mod
 * @param stdClass $page
 * @return string the user complete information
 */
function certificate_user_complete($course, $user, $mod, $certificate) {
   global $DB, $OUTPUT;

   if ($issue = $DB->get_record('certificate_issues', array('certificateid' => $certificate->id, 'userid' => $user->id))) {
        echo $OUTPUT->box_start();
        echo get_string('issued', 'certificate') . ": ";
        echo userdate($issue->timecreated);
        certificate_print_user_files($certificate->id, $user->id);
        echo '<br />';
        echo $OUTPUT->box_end();
    } else {
        print_string('notissuedyet', 'certificate');
    }
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of certificate.
 *
 * @param int $certificateid
 * @return stdClass list of participants
 */
function certificate_get_participants($certificateid) {
    global $DB;

    $sql = "SELECT DISTINCT u.id, u.id
            FROM {user} u, {certificate_issues} a
            WHERE a.certificateid = :certificateid
            AND u.id = a.userid";
    return  $DB->get_records_sql($sql, array('certificateid' => $certificateid));
}

/**
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, null if doesn't know
 */
function certificate_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:                  return true;
        case FEATURE_GROUPINGS:               return true;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_BACKUP_MOODLE2:          return true;

        default: return null;
    }
}

/**
 * Function to be run periodically according to the moodle cron
 * TODO:This needs to be done
 */
function certificate_cron () {
    return true;
}

/**
 * Returns a list of teachers by group
 * for sending email alerts to teachers
 *
 * @param stdClass $certificate
 * @param stdClass $user
 * @param stdClass $course
 * @param stdClass $cm
 * @return array the teacher array
 */
function certificate_get_teachers($certificate, $user, $course, $cm) {
    global $USER, $DB;

    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $potteachers = get_users_by_capability($context, 'mod/certificate:manage',
        '', '', '', '', '', '', false, false);
    if (empty($potteachers)) {
        return array();
    }
    $teachers = array();
    if (groups_get_activity_groupmode($cm, $course) == SEPARATEGROUPS) {   // Separate groups are being used
        if ($groups = groups_get_all_groups($course->id, $user->id)) {  // Try to find all groups
            foreach ($groups as $group) {
                foreach ($potteachers as $t) {
                    if ($t->id == $user->id) {
                        continue; // do not send self
                    }
                    if (groups_is_member($group->id, $t->id)) {
                        $teachers[$t->id] = $t;
                    }
                }
            }
        } else {
            // user not in group, try to find teachers without group
            foreach ($potteachers as $t) {
                if ($t->id == $USER->id) {
                    continue; // do not send self
                }
                if (!groups_get_all_groups($course->id, $t->id)) { //ugly hack
                    $teachers[$t->id] = $t;
                }
            }
        }
    } else {
        foreach ($potteachers as $t) {
            if ($t->id == $USER->id) {
                continue; // do not send self
            }
            $teachers[$t->id] = $t;
        }
    }

    return $teachers;
}

/**
 * Alerts teachers by email of received certificates. First checks
 * whether the option to email teachers is set for this certificate.
 *
 * @param stdClass $course
 * @param stdClass $certificate
 * @param stdClass $certrecord
 * @param stdClass $cm course module
 */
function certificate_email_teachers($course, $certificate, $certrecord, $cm) {
    global $USER, $CFG, $DB;

    if ($certificate->emailteachers == 0) {          // No need to do anything
        return;
    }

    $user = $DB->get_record('user', array('id' => $certrecord->userid));

    if ($teachers = certificate_get_teachers($certificate, $user, $course, $cm)) {
        $strawarded = get_string('awarded', 'certificate');
        foreach ($teachers as $teacher) {
            $info = new stdClass;
            $info->student = fullname($USER);
            $info->course = format_string($course->fullname,true);
            $info->certificate = format_string($certificate->name,true);
            $info->url = $CFG->wwwroot.'/mod/certificate/report.php?id='.$cm->id;
            $from = $USER;
            $postsubject = $strawarded . ': ' . $info->student . ' -> ' . $certificate->name;
            $posttext = certificate_email_teachers_text($info);
            $posthtml = ($teacher->mailformat == 1) ? certificate_email_teachers_html($info) : '';

            @email_to_user($teacher, $from, $postsubject, $posttext, $posthtml);  // If it fails, oh well, too bad.
        }
    }
}

/**
 * Alerts others by email of received certificates. First checks
 * whether the option to email others is set for this certificate.
 * Uses the email_teachers info.
 * Code suggested by Eloy Lafuente
 *
 * @param stdClass $course
 * @param stdClass $certificate
 * @param stdClass $certrecord
 * @param stdClass $cm course module
 */
function certificate_email_others($course, $certificate, $certrecord, $cm) {
    global $USER, $CFG, $DB;

    if ($certificate->emailothers) {
       $others = explode(',', $certificate->emailothers);
        if ($others) {
            $strawarded = get_string('awarded', 'certificate');
            foreach ($others as $other) {
                $other = trim($other);
                if (validate_email($other)) {
                    $destination = new stdClass;
                    $destination->email = $other;
                    $info = new stdClass;
                    $info->student = fullname($USER);
                    $info->course = format_string($course->fullname, true);
                    $info->certificate = format_string($certificate->name, true);
                    $info->url = $CFG->wwwroot.'/mod/certificate/report.php?id='.$cm->id;
                    $from = $USER;
                    $postsubject = $strawarded . ': ' . $info->student . ' -> ' . $certificate->name;
                    $posttext = certificate_email_teachers_text($info);
                    $posthtml = certificate_email_teachers_html($info);

                    @email_to_user($destination, $from, $postsubject, $posttext, $posthtml);  // If it fails, oh well, too bad.
                }
            }
        }
    }
}

/**
 * Creates the text content for emails to teachers -- needs to be finished with cron
 *
 * @param $info object The info used by the 'emailteachermail' language string
 * @return string
 */
function certificate_email_teachers_text($info) {
    $posttext = get_string('emailteachermail', 'certificate', $info) . "\n";

    return $posttext;
}

/**
 * Creates the html content for emails to teachers
 *
 * @param $info object The info used by the 'emailteachermailhtml' language string
 * @return string
 */
function certificate_email_teachers_html($info) {
    $posthtml  = '<font face="sans-serif">';
    $posthtml .= '<p>' . get_string('emailteachermailhtml', 'certificate', $info) . '</p>';
    $posthtml .= '</font>';

    return $posthtml;
}

/**
 * Sends the student their issued certificate from moddata as an email
 * attachment.
 *
 * @param stdClass $course
 * @param stdClass $certificate
 * @param stdClass $certrecord
 * @param stdClass $context
 */
function certificate_email_student($course, $certificate, $certrecord, $context) {
    global $DB, $USER;

    // Get teachers
    if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
        '', '', '', '', false, true)) {
        $users = sort_by_roleassignment_authority($users, $context);
        $teacher = array_shift($users);
    }

    // If we haven't found a teacher yet, look for a non-editing teacher in this course.
    if (empty($teacher) && $users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
        '', '', '', '', false, true)) {
        $users = sort_by_roleassignment_authority($users, $context);
        $teacher = array_shift($users);
    }

    // Ok, no teachers, use administrator name
    if (empty($teacher)) {
        $teacher = fullname(get_admin());
    }

    $info = new stdClass;
    $info->username = fullname($USER);
    $info->certificate = format_string($certificate->name, true);
    $info->course = format_string($course->fullname, true);
    $from = fullname($teacher);
    $subject = $info->course . ': ' . $info->certificate;
    $message = get_string('emailstudenttext', 'certificate', $info) . "\n";

    // Make the HTML version more XHTML happy  (&amp;)
    $messagehtml = text_to_html(get_string('emailstudenttext', 'certificate', $info));

    // Remove full-stop at the end if it exists, to avoid "..pdf" being created and being filtered by clean_filename
    $certname = rtrim($certificate->name, '.');
    $filename = clean_filename("$certname.pdf");

    // Get hashed pathname
    $fs = get_file_storage();

    $component = 'mod_certificate';
    $filearea = 'issue';
    $filepath = '/';
    $files = $fs->get_area_files($context->id, $component, $filearea, $certrecord->id);
    foreach ($files as $f) {
        $filepathname = $f->get_contenthash();
    }
    $attachment = 'filedir/'.certificate_path_from_hash($filepathname).'/'.$filepathname;
    $attachname = $filename;

    return email_to_user($USER, $from, $subject, $message, $messagehtml, $attachment, $attachname);
}

/**
 * Retrieve certificate path from hash
 *
 * @param array $contenthash
 * @return string the path
 */
function certificate_path_from_hash($contenthash) {
    $l1 = $contenthash[0].$contenthash[1];
    $l2 = $contenthash[2].$contenthash[3];
    return "$l1/$l2";
}

/**
 * Serves certificate issues and other files.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool|nothing false if file not found, does not return anything if found - just send the file
 */
function certificate_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    global $CFG, $DB, $USER;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    if (!$certificate = $DB->get_record('certificate', array('id' => $cm->instance))) {
        return false;
    }

    require_login($course, false, $cm);

    require_once($CFG->libdir.'/filelib.php');

    if ($filearea === 'issue') {
        $certrecord = (int)array_shift($args);

        if (!$certrecord = $DB->get_record('certificate_issues', array('id' => $certrecord))) {
            return false;
        }

        if ($USER->id != $certrecord->userid and !has_capability('mod/certificate:manage', $context)) {
            return false;
        }

        $relativepath = implode('/', $args);
        $fullpath = "/{$context->id}/mod_certificate/issue/$certrecord->id/$relativepath";

        $fs = get_file_storage();
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }
        send_stored_file($file, 0, 0, true); // download MUST be forced - security!
    }
}

/**
 * This function returns success or failure of file save
 *
 * @param string $pdf is the string contents of the pdf
 * @param int $certrecordid the certificate issue record id
 * @param string $filename pdf filename
 * @param int $contextid context id
 * @return bool return true if successful, false otherwise
 */
function certificate_save_pdf($pdf, $certrecordid, $filename, $contextid) {
    global $DB, $USER;

    if (empty($certrecordid)) {
        return false;
    }

    if (empty($pdf)) {
        return false;
    }

    $fs = get_file_storage();

    // Prepare file record object
    $component = 'mod_certificate';
    $filearea = 'issue';
    $filepath = '/';
    $fileinfo = array(
        'contextid' => $contextid,   // ID of context
        'component' => $component,   // usually = table name
        'filearea'  => $filearea,     // usually = table name
        'itemid'    => $certrecordid,  // usually = ID of row in table
        'filepath'  => $filepath,     // any path beginning and ending in /
        'filename'  => $filename,    // any filename
        'mimetype'  => 'application/pdf',    // any filename
        'userid'    => $USER->id);

    // If the file exists, delete it and recreate it. This is to ensure that the
    // latest certificate is saved on the server. For example, the student's grade
    // may have been updated. This is a quick dirty hack.
    if ($fs->file_exists($contextid, $component, $filearea, $certrecordid, $filepath, $filename)) {
        $fs->delete_area_files($contextid, $component, $filearea, $certrecordid);
    }

    $fs->create_file_from_string($fileinfo, $pdf);

    return true;
}

/**
 * Produces a list of links to the issued certificates.  Used for report.
 *
 * @param stdClass $certificate
 * @param int $userid
 * @param stdClass $context
 * @return string return the user files
 */
function certificate_print_user_files($certificate, $userid, $context) {
    global $CFG, $DB, $OUTPUT;

    $output = '';

    $certrecord = $DB->get_record('certificate_issues', array('userid' => $userid, 'certificateid' => $certificate->id));
    $fs = get_file_storage();
    $browser = get_file_browser();

    $component = 'mod_certificate';
    $filearea = 'issue';
    $files = $fs->get_area_files($context, $component, $filearea, $certrecord->id);
    foreach ($files as $file) {
        $filename = $file->get_filename();
        $mimetype = $file->get_mimetype();
        $link = file_encode_url($CFG->wwwroot.'/pluginfile.php', '/'.$context.'/mod_certificate/issue/'.$certrecord->id.'/'.$filename);

        $output = '<img src="'.$OUTPUT->pix_url(file_mimetype_icon($file->get_mimetype())).'" height="16" width="16" alt="'.$file->get_mimetype().'" />&nbsp;'.
                  '<a href="'.$link.'" >'.s($filename).'</a>';

    }
    $output .= '<br />';
    $output = '<div class="files">'.$output.'</div>';

    return $output;
}

/**
 * Inserts preliminary user data when a certificate is viewed.
 * Prevents form from issuing a certificate upon browser refresh.
 *
 * @param stdClass $course
 * @param stdClass $user
 * @param stdClass $certificate
 * @param stdClass $cm
 * @return stdClass the newly created certificate issue
 */
function certificate_get_issue($course, $user, $certificate, $cm) {
    global $DB;

    // Check if there is an issue already, should only ever be one
    if ($certissue = $DB->get_record('certificate_issues', array('userid' => $user->id, 'certificateid' => $certificate->id))) {
        return $certissue;
    }

    // Create new certificate issue record
    $certissue = new stdClass();
    $certissue->certificateid = $certificate->id;
    $certissue->userid = $user->id;
    $certissue->code = certificate_generate_code();
    $certissue->timecreated =  time();
    $certissue->id = $DB->insert_record('certificate_issues', $certissue);

    // Email to the teachers and anyone else
    certificate_email_teachers($course, $certificate, $certissue, $cm);
    certificate_email_others($course, $certificate, $certissue, $cm);

    return $certissue;
}

/**
 * Returns a list of issued certificates - sorted for report.
 *
 * @param int $certificateid
 * @param string $sort the sort order
 * @param bool $groupmode are we in group mode ?
 * @param stdClass $cm the course module
 * @param int $page offset
 * @param int $perpage total per page
 * @return stdClass the users
 */
function certificate_get_issues($certificateid, $sort="ci.timecreated ASC", $groupmode, $cm, $page = 0, $perpage = 0) {
    global $CFG, $DB;

    // get all users that can manage this certificate to exclude them from the report.
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $certmanagers = get_users_by_capability($context, 'mod/certificate:manage', 'u.id');

    $page = (int) $page;
    $perpage = (int) $perpage;

    // Setup pagination - when both $page and $perpage = 0, get all results
    if ($page || $perpage) {
        if ($page < 0) {
            $page = 0;
        }

        if ($perpage > CERT_MAX_PER_PAGE) {
            $perpage = CERT_MAX_PER_PAGE;
        } else if ($perpage < 1) {
            $perpage = CERT_PER_PAGE;
        }
    }

    // Get all the users that have certificates issued, should only be one issue per user for a certificate
    $users = $DB->get_records_sql("SELECT u.*, ci.code, ci.timecreated
                                   FROM {user} u
                                   INNER JOIN {certificate_issues} ci
                                   ON u.id = ci.userid
                                   WHERE u.deleted = 0
                                   AND ci.certificateid = :certificateid
                                   ORDER BY {$sort}", array('certificateid' => $certificateid),
                                   $page * $perpage,
                                   $perpage);

    // now exclude all the certmanagers.
    foreach ($users as $id => $user) {
        if (isset($certmanagers[$id])) { //exclude certmanagers.
            unset($users[$id]);
        }
    }

    // if groupmembersonly used, remove users who are not in any group
    if (!empty($users) and !empty($CFG->enablegroupings) and $cm->groupmembersonly) {
        if ($groupingusers = groups_get_grouping_members($cm->groupingid, 'u.id', 'u.id')) {
            $users = array_intersect($users, array_keys($groupingusers));
        }
    }

    if ($groupmode) {
        $currentgroup = groups_get_activity_group($cm);
        if ($currentgroup) {
            $groupusers = groups_get_members($currentgroup, 'u.*');
            if (empty($groupusers)) {
                return array();
            }
            foreach($users as $id => $unused) {
                if (!isset($groupusers[$id])) {
                    // remove this user as it isn't in the group!
                    unset($users[$id]);
                }
            }
        }
    }

    return $users;
}

/**
 * Returns a list of previously issued certificates--used for reissue.
 *
 * @param int $certificateid
 * @return stdClass the attempts else false if none found
 */
function certificate_get_attempts($certificateid) {
    global $DB, $USER;

    $sql = "SELECT *
            FROM {certificate_issues} i
            WHERE certificateid = :certificateid
            AND userid = :userid";
    if ($issues = $DB->get_records_sql($sql, array('certificateid' => $certificateid, 'userid' => $USER->id))) {
        return $issues;
    }

    return false;
}

/**
 * Prints a table of previously issued certificates--used for reissue.
 *
 * @param stdClass $course
 * @param stdClass $certificate
 * @param stdClass $attempts
 * @return string the attempt table
 */
function certificate_print_attempts($course, $certificate, $attempts) {
    global $OUTPUT, $DB;

    echo $OUTPUT->heading(get_string('summaryofattempts', 'certificate'));

    // Prepare table header
    $table = new html_table();
    $table->class = 'generaltable';
    $table->head = array(get_string('issued', 'certificate'));
    $table->align = array('left');
    $table->attributes = array("style" => "width:20%; margin:auto");
    $gradecolumn = $certificate->printgrade;
    if ($gradecolumn) {
        $table->head[] = get_string('grade');
        $table->align[] = 'center';
        $table->size[] = '';
    }
    // One row for each attempt
    foreach ($attempts as $attempt) {
        $row = array();

        // prepare strings for time taken and date completed
        $datecompleted = userdate($attempt->timecreated);
        $row[] = $datecompleted;

        if ($gradecolumn) {
            $attemptgrade = certificate_get_grade($certificate, $course);
            $row[] = $attemptgrade;
        }

        $table->data[$attempt->id] = $row;
    }

    echo html_writer::table($table);
}

/**
 * Get the time the user has spent in the course
 *
 * @param int $courseid
 * @return int the total time spent in seconds
 */
function certificate_get_course_time($courseid) {
    global $CFG, $USER;

    set_time_limit(0);

    $totaltime = 0;
    $sql = "l.course = :courseid AND l.userid = :userid";
    if ($logs = get_logs($sql, array('courseid' => $courseid, 'userid' => $USER->id), 'l.time ASC', '', '', $totalcount)) {
        foreach ($logs as $log) {
            if (!isset($login)) {
                // For the first time $login is not set so the first log is also the first login
                $login = $log->time;
                $lasthit = $log->time;
                $totaltime = 0;
            }
            $delay = $log->time - $lasthit;
            if ($delay > ($CFG->sessiontimeout * 60)) {
                // The difference between the last log and the current log is more than
                // the timeout Register session value so that we have found a session!
                $login = $log->time;
            } else {
                $totaltime += $delay;
            }
            // Now the actual log became the previous log for the next cycle
            $lasthit = $log->time;
        }

        return $totaltime;
    }

    return 0;
}

/**
 * Get all the modules
 *
 * @return array
 */
function certificate_get_mods() {
    global $COURSE, $CFG, $DB;

    $strtopic = get_string("topic");
    $strweek = get_string("week");
    $strsection = get_string("section");

    // Collect modules data
    $modinfo = get_fast_modinfo($COURSE);
    $mods = $modinfo->get_cms();

    $modules = array();
    // Check what version we are running - really we should have separate branch for 2.4, but
    // having a branch called master and one called MOODLE_24_STABLE may be confusing. This
    // module will also be replaced in the future so hack will do. Here we get the course
    // sections and sort the modules as they appear in the course.
    if ($CFG->version >= '2012112900') {
        $sections = $modinfo->get_section_info_all();
    } else {
        $sections = get_all_sections($COURSE->id);
    }
    for ($i = 0; $i <= count($sections) - 1; $i++) {
        // should always be true
        if (isset($sections[$i])) {
            $section = $sections[$i];
            if ($section->sequence) {
                switch ($COURSE->format) {
                    case "topics":
                        $sectionlabel = $strtopic;
                    break;
                    case "weeks":
                        $sectionlabel = $strweek;
                    break;
                    default:
                        $sectionlabel = $strsection;
                }

                $sectionmods = explode(",", $section->sequence);
                foreach ($sectionmods as $sectionmod) {
                    if (empty($mods[$sectionmod])) {
                        continue;
                    }
                    $mod = $mods[$sectionmod];
                    $mod->courseid = $COURSE->id;
                    $instance = $DB->get_record($mod->modname, array('id' => $mod->instance));
                    if ($grade_items = grade_get_grade_items_for_activity($mod)) {
                        $mod_item = grade_get_grades($COURSE->id, 'mod', $mod->modname, $mod->instance);
                        $item = reset($mod_item->items);
                        if (isset($item->grademax)){
                            $modules[$mod->id] = $sectionlabel . ' ' . $section->section . ' : ' . $instance->name;
                        }
                    }
                }
            }
        }
    }

    return $modules;
}

/**
 * Search through all the modules for grade data for mod_form.
 *
 * @return array
 */
function certificate_get_grade_options() {
    $gradeoptions['0'] = get_string('no');
    $gradeoptions['1'] = get_string('coursegrade', 'certificate');

    return $gradeoptions;
}

/**
 * Search through all the modules for grade dates for mod_form.
 *
 * @return array
 */
function certificate_get_date_options() {
    $dateoptions['0'] = get_string('no');
    $dateoptions['1'] = get_string('issueddate', 'certificate');
    $dateoptions['2'] = get_string('completiondate', 'certificate');

    return $dateoptions;
}

/**
 * Get the course outcomes for for mod_form print outcome.
 *
 * @return array
 */
function certificate_get_outcomes() {
    global $COURSE, $DB;

    // get all outcomes in course
    $grade_seq = new grade_tree($COURSE->id, false, true, '', false);
    if ($grade_items = $grade_seq->items) {
        // list of item for menu
        $printoutcome = array();
        foreach ($grade_items as $grade_item) {
            if (isset($grade_item->outcomeid)){
                $itemmodule = $grade_item->itemmodule;
                $printoutcome[$grade_item->id] = $itemmodule . ': ' . $grade_item->get_name();
            }
        }
    }
    if (isset($printoutcome)) {
        $outcomeoptions['0'] = get_string('no');
        foreach ($printoutcome as $key => $value) {
            $outcomeoptions[$key] = $value;
        }
    } else {
        $outcomeoptions['0'] = get_string('nooutcomes', 'certificate');
    }

    return $outcomeoptions;
}

/**
 * Used for course participation report (in case certificate is added).
 *
 * @return array
 */
function certificate_get_view_actions() {
    return array('view', 'view all', 'view report');
}

/**
 * Used for course participation report (in case certificate is added).
 *
 * @return array
 */
function certificate_get_post_actions() {
    return array('received');
}

/**
 * Get certificate types indexed and sorted by name for mod_form.
 *
 * @return array containing the certificate type
 */
function certificate_types() {
    $types = array();
    $names = get_list_of_plugins('mod/certificate/type');
    $sm = get_string_manager();
    foreach ($names as $name) {
        if ($sm->string_exists('type'.$name, 'certificate')) {
            $types[$name] = get_string('type'.$name, 'certificate');
        } else {
            $types[$name] = ucfirst($name);
        }
    }
    asort($types);
    return $types;
}

/**
 * Get images for mod_form.
 *
 * @param string $type the image type
 * @return array
 */
function certificate_get_images($type) {
    global $CFG, $DB;

    switch($type) {
        case CERT_IMAGE_BORDER :
            $path = "$CFG->dirroot/mod/certificate/pix/borders";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/borders";
            break;
        case CERT_IMAGE_SEAL :
            $path = "$CFG->dirroot/mod/certificate/pix/seals";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/seals";
            break;
        case CERT_IMAGE_SIGNATURE :
            $path = "$CFG->dirroot/mod/certificate/pix/signatures";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/signatures";
            break;
        case CERT_IMAGE_WATERMARK :
            $path = "$CFG->dirroot/mod/certificate/pix/watermarks";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/watermarks";
            break;
    }
    // If valid path
    if (!empty($path)) {
        $options = array();
        $options += certificate_scan_image_dir($path);
        $options += certificate_scan_image_dir($uploadpath);

        // Sort images
        ksort($options);

        // Add the 'no' option to the top of the array
        $options = array_merge(array('0' => get_string('no')), $options);

        return $options;
    } else {
        return array();
    }
}

/**
 * Prepare to print an activity grade.
 *
 * @param stdClass $course
 * @param int $moduleid
 * @param int $userid
 * @return stdClass|bool return the mod object if it exists, false otherwise
 */
function certificate_get_mod_grade($course, $moduleid, $userid) {
    global $DB;

    $cm = $DB->get_record('course_modules', array('id' => $moduleid));
    $module = $DB->get_record('modules', array('id' => $cm->module));

    if ($grade_item = grade_get_grades($course->id, 'mod', $module->name, $cm->instance, $userid)) {
        $item = new grade_item();
        $itemproperties = reset($grade_item->items);
        foreach ($itemproperties as $key => $value) {
            $item->$key = $value;
        }
        $modinfo = new stdClass;
        $modinfo->name = utf8_decode($DB->get_field($module->name, 'name', array('id' => $cm->instance)));
        $grade = $item->grades[$userid]->grade;
        $item->gradetype = GRADE_TYPE_VALUE;
        $item->courseid = $course->id;

        $modinfo->points = grade_format_gradevalue($grade, $item, true, GRADE_DISPLAY_TYPE_REAL, $decimals = 2);
        $modinfo->percentage = grade_format_gradevalue($grade, $item, true, GRADE_DISPLAY_TYPE_PERCENTAGE, $decimals = 2);
        $modinfo->letter = grade_format_gradevalue($grade, $item, true, GRADE_DISPLAY_TYPE_LETTER, $decimals = 0);

        if ($grade) {
            $modinfo->dategraded = $item->grades[$userid]->dategraded;
        } else {
            $modinfo->dategraded = time();
        }
        return $modinfo;
    }

    return false;
}

/**
 * Returns the date to display for the certificate.
 *
 * @param stdClass $certificate
 * @param stdClass $certrecord
 * @param stdClass $course
 * @param int $userid
 * @return string the date
 */
function certificate_get_date($certificate, $certrecord, $course, $userid = null) {
    global $DB, $USER;

    if (empty($userid)) {
        $userid = $USER->id;
    }

    // Set certificate date to current time, can be overwritten later
    $date = $certrecord->timecreated;

    if ($certificate->printdate == '2') {
        // Get the enrolment end date
        $sql = "SELECT MAX(c.timecompleted) as timecompleted
                FROM {course_completions} c
                WHERE c.userid = :userid
                AND c.course = :courseid";
        if ($timecompleted = $DB->get_record_sql($sql, array('userid' => $userid, 'courseid' => $course->id))) {
            if (!empty($timecompleted->timecompleted)) {
                $date = $timecompleted->timecompleted;
            }
        }
    } else if ($certificate->printdate > 2) {
        if ($modinfo = certificate_get_mod_grade($course, $certificate->printdate, $userid)) {
            $date = $modinfo->dategraded;
        }
    }
    if ($certificate->printdate > 0) {
        if ($certificate->datefmt == 1) {
            $certificatedate = str_replace(' 0', ' ', strftime('%B %d, %Y', $date));
        } else if ($certificate->datefmt == 2) {
            $certificatedate = date('F jS, Y', $date);
        } else if ($certificate->datefmt == 3) {
            $certificatedate = str_replace(' 0', '', strftime('%d %B %Y', $date));
        } else if ($certificate->datefmt == 4) {
            $certificatedate = strftime('%B %Y', $date);
        } else if ($certificate->datefmt == 5) {
            $certificatedate = userdate($date, get_string('strftimedate', 'langconfig'));
        }

        return $certificatedate;
    }

    return '';
}

/**
 * Returns the grade to display for the certificate.
 *
 * @param stdClass $certificate
 * @param stdClass $course
 * @param int $userid
 * @return string the grade result
 */
function certificate_get_grade($certificate, $course, $userid = null) {
    global $USER, $DB;

    if (empty($userid)) {
        $userid = $USER->id;
    }

    if ($certificate->printgrade > 0) {
        if ($certificate->printgrade == 1) {
            if ($course_item = grade_item::fetch_course_item($course->id)) {
                // String used
                $strcoursegrade = get_string('coursegrade', 'certificate');

                $grade = new grade_grade(array('itemid' => $course_item->id, 'userid' => $userid));
                $course_item->gradetype = GRADE_TYPE_VALUE;
                $coursegrade = new stdClass;
                $coursegrade->points = grade_format_gradevalue($grade->finalgrade, $course_item, true, GRADE_DISPLAY_TYPE_REAL, $decimals = 2);
                $coursegrade->percentage = grade_format_gradevalue($grade->finalgrade, $course_item, true, GRADE_DISPLAY_TYPE_PERCENTAGE, $decimals = 2);
                $coursegrade->letter = grade_format_gradevalue($grade->finalgrade, $course_item, true, GRADE_DISPLAY_TYPE_LETTER, $decimals = 0);

                if ($certificate->gradefmt == 1) {
                    $grade = $strcoursegrade . ':  ' . $coursegrade->percentage;
                } else if ($certificate->gradefmt == 2) {
                    $grade = $strcoursegrade . ':  ' . $coursegrade->points;
                } else if ($certificate->gradefmt == 3) {
                    $grade = $strcoursegrade . ':  ' . $coursegrade->letter;
                }

                return $grade;
            }
        } else { // Print the mod grade
            if ($modinfo = certificate_get_mod_grade($course, $certificate->printgrade, $userid)) {
                // String used
                $strgrade = get_string('grade', 'certificate');
                if ($certificate->gradefmt == 1) {
                    $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->percentage;
                } else if ($certificate->gradefmt == 2) {
                    $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->points;
                } else if ($certificate->gradefmt == 3) {
                    $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modinfo->letter;
                }

                return $grade;
            }
        }
    }

    return '';
}

/**
 * Returns the outcome to display on the certificate
 *
 * @param stdClass $certificate
 * @param stdClass $course
 * @return string the outcome
 */
function certificate_get_outcome($certificate, $course) {
    global $USER, $DB;

    if ($certificate->printoutcome > 0) {
        if ($grade_item = new grade_item(array('id' => $certificate->printoutcome))) {
            $outcomeinfo = new stdClass;
            $outcomeinfo->name = $grade_item->get_name();
            $outcome = new grade_grade(array('itemid' => $grade_item->id, 'userid' => $USER->id));
            $outcomeinfo->grade = grade_format_gradevalue($outcome->finalgrade, $grade_item, true, GRADE_DISPLAY_TYPE_REAL);

            return $outcomeinfo->name . ': ' . $outcomeinfo->grade;
        }
    }

    return '';
}

/**
 * Returns the code to display on the certificate.
 *
 * @param stdClass $course
 * @param stdClass $certrecord
 * @return string the code
 */
function certificate_get_code($certificate, $certrecord) {
    if ($certificate->printnumber) {
        return $certrecord->code;
    }

    return '';
}

/**
 * Sends text to output given the following params.
 *
 * @param stdClass $pdf
 * @param int $x horizontal position
 * @param int $y vertical position
 * @param char $align L=left, C=center, R=right
 * @param string $font any available font in font directory
 * @param char $style ''=normal, B=bold, I=italic, U=underline
 * @param int $size font size in points
 * @param string $text the text to print
 */
function certificate_print_text($pdf, $x, $y, $align, $font='freeserif', $style, $size=10, $text) {
    $pdf->setFont($font, $style, $size);
    $pdf->SetXY($x, $y);
    $pdf->writeHTMLCell(0, 0, '', '', $text, 0, 0, 0, true, $align);
}

/**
 * Creates rectangles for line border for A4 size paper.
 *
 * @param stdClass $pdf
 * @param stdClass $certificate
 */
function certificate_draw_frame($pdf, $certificate) {
    if ($certificate->bordercolor > 0) {
        if ($certificate->bordercolor == 1) {
            $color = array(0, 0, 0); // black
        }
        if ($certificate->bordercolor == 2) {
            $color = array(153, 102, 51); // brown
        }
        if ($certificate->bordercolor == 3) {
            $color = array(0, 51, 204); // blue
        }
        if ($certificate->bordercolor == 4) {
            $color = array(0, 180, 0); // green
        }
        switch ($certificate->orientation) {
            case 'L':
                // create outer line border in selected color
                $pdf->SetLineStyle(array('width' => 1.5, 'color' => $color));
                $pdf->Rect(10, 10, 277, 190);
                // create middle line border in selected color
                $pdf->SetLineStyle(array('width' => 0.2, 'color' => $color));
                $pdf->Rect(13, 13, 271, 184);
                // create inner line border in selected color
                $pdf->SetLineStyle(array('width' => 1.0, 'color' => $color));
                $pdf->Rect(16, 16, 265, 178);
            break;
            case 'P':
                // create outer line border in selected color
                $pdf->SetLineStyle(array('width' => 1.5, 'color' => $color));
                $pdf->Rect(10, 10, 190, 277);
                // create middle line border in selected color
                $pdf->SetLineStyle(array('width' => 0.2, 'color' => $color));
                $pdf->Rect(13, 13, 184, 271);
                // create inner line border in selected color
                $pdf->SetLineStyle(array('width' => 1.0, 'color' => $color));
                $pdf->Rect(16, 16, 178, 265);
            break;
        }
    }
}

/**
 * Creates rectangles for line border for letter size paper.
 *
 * @param stdClass $pdf
 * @param stdClass $certificate
 */
function certificate_draw_frame_letter($pdf, $certificate) {
    if ($certificate->bordercolor > 0) {
        if ($certificate->bordercolor == 1)    {
            $color = array(0, 0, 0); //black
        }
        if ($certificate->bordercolor == 2)    {
            $color = array(153, 102, 51); //brown
        }
        if ($certificate->bordercolor == 3)    {
            $color = array(0, 51, 204); //blue
        }
        if ($certificate->bordercolor == 4)    {
            $color = array(0, 180, 0); //green
        }
        switch ($certificate->orientation) {
            case 'L':
                // create outer line border in selected color
                $pdf->SetLineStyle(array('width' => 4.25, 'color' => $color));
                $pdf->Rect(28, 28, 736, 556);
                // create middle line border in selected color
                $pdf->SetLineStyle(array('width' => 0.2, 'color' => $color));
                $pdf->Rect(37, 37, 718, 538);
                // create inner line border in selected color
                $pdf->SetLineStyle(array('width' => 2.8, 'color' => $color));
                $pdf->Rect(46, 46, 700, 520);
                break;
            case 'P':
                // create outer line border in selected color
                $pdf->SetLineStyle(array('width' => 1.5, 'color' => $color));
                $pdf->Rect(25, 20, 561, 751);
                // create middle line border in selected color
                $pdf->SetLineStyle(array('width' => 0.2, 'color' => $color));
                $pdf->Rect(40, 35, 531, 721);
                // create inner line border in selected color
                $pdf->SetLineStyle(array('width' => 1.0, 'color' => $color));
                $pdf->Rect(51, 46, 509, 699);
            break;
        }
    }
}

/**
 * Prints border images from the borders folder in PNG or JPG formats.
 *
 * @param stdClass $pdf;
 * @param stdClass $certificate
 * @param int $x x position
 * @param int $y y position
 * @param int $w the width
 * @param int $h the height
 */
function certificate_print_image($pdf, $certificate, $type, $x, $y, $w, $h) {
    global $CFG;

    switch($type) {
        case CERT_IMAGE_BORDER :
            $attr = 'borderstyle';
            $path = "$CFG->dirroot/mod/certificate/pix/$type/$certificate->borderstyle";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/$type/$certificate->borderstyle";
            break;
        case CERT_IMAGE_SEAL :
            $attr = 'printseal';
            $path = "$CFG->dirroot/mod/certificate/pix/$type/$certificate->printseal";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/$type/$certificate->printseal";
            break;
        case CERT_IMAGE_SIGNATURE :
            $attr = 'printsignature';
            $path = "$CFG->dirroot/mod/certificate/pix/$type/$certificate->printsignature";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/$type/$certificate->printsignature";
            break;
        case CERT_IMAGE_WATERMARK :
            $attr = 'printwmark';
            $path = "$CFG->dirroot/mod/certificate/pix/$type/$certificate->printwmark";
            $uploadpath = "$CFG->dataroot/mod/certificate/pix/$type/$certificate->printwmark";
            break;
    }
    // Has to be valid
    if (!empty($path)) {
        switch ($certificate->$attr) {
            case '0' :
            case '' :
            break;
            default :
                if (file_exists($path)) {
                    $pdf->Image($path, $x, $y, $w, $h);
                }
                if (file_exists($uploadpath)) {
                    $pdf->Image($uploadpath, $x, $y, $w, $h);
                }
            break;
        }
    }
}

/**
 * Generates a 10-digit code of random letters and numbers.
 *
 * @return string
 */
function certificate_generate_code() {
    global $DB;

    $uniquecodefound = false;
    $code = random_string(10);
    while (!$uniquecodefound) {
        if (!$DB->record_exists('certificate_issues', array('code' => $code))) {
            $uniquecodefound = true;
        } else {
            $code = random_string(10);
        }
    }

    return $code;
}

/**
 * Scans directory for valid images
 *
 * @param string the path
 * @return array
 */
function certificate_scan_image_dir($path) {
    // Array to store the images
    $options = array();

    // Start to scan directory
    if (is_dir($path)) {
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if (strpos($file, '.png', 1) || strpos($file, '.jpg', 1) ) {
                    $i = strpos($file, '.');
                    if ($i > 1) {
                        // Set the name
                        $options[$file] = substr($file, 0, $i);
                    }
                }
            }
            closedir($handle);
        }
    }

    return $options;
}