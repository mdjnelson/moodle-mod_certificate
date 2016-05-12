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
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add certificate instance.
 *
 * @param stdClass $certificate
 * @return int new certificate instance id
 */
function certificate_add_instance($certificate) {
    global $DB;

    // Create the certificate.
    $certificate->timecreated = time();
    $certificate->timemodified = $certificate->timecreated;

    return $DB->insert_record('certificate', $certificate);
}

/**
 * Update certificate instance.
 *
 * @param stdClass $certificate
 * @return bool true
 */
function certificate_update_instance($certificate) {
    global $DB;

    // Update the certificate.
    $certificate->timemodified = time();
    $certificate->id = $certificate->instance;

    return $DB->update_record('certificate', $certificate);
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
    $context = context_module::instance($cm->id);
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
    global $DB;

    $componentstr = get_string('modulenameplural', 'certificate');
    $status = array();

    if (!empty($data->reset_certificate)) {
        $sql = "SELECT cert.id
                  FROM {certificate} cert
                 WHERE cert.course = :courseid";
        $params = array('courseid' => $data->courseid);
        $certificates = $DB->get_records_sql($sql, $params);
        $fs = get_file_storage();
        if ($certificates) {
            foreach ($certificates as $certid => $unused) {
                if (!$cm = get_coursemodule_from_instance('certificate', $certid)) {
                    continue;
                }
                $context = context_module::instance($cm->id);
                $fs->delete_area_files($context->id, 'mod_certificate', 'issue');
            }
        }

        $DB->delete_records_select('certificate_issues', "certificateid IN ($sql)", $params);
        $status[] = array('component' => $componentstr, 'item' => get_string('removecert', 'certificate'), 'error' => false);
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
 * @param stdClass $certificate
 * @return string the user complete information
 */
function certificate_user_complete($course, $user, $mod, $certificate) {
    global $DB, $OUTPUT, $CFG;
    require_once($CFG->dirroot.'/mod/certificate/locallib.php');

    if ($issue = $DB->get_record('certificate_issues', array('certificateid' => $certificate->id, 'userid' => $user->id))) {
        echo $OUTPUT->box_start();
        echo get_string('issued', 'certificate') . ": ";
        echo userdate($issue->timecreated);
        $cm = get_coursemodule_from_instance('certificate', $certificate->id, $course->id);
        certificate_print_user_files($certificate, $user->id, context_module::instance($cm->id)->id);
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

    $certrecord = (int)array_shift($args);

    if (!$certrecord = $DB->get_record('certificate_issues', array('id' => $certrecord))) {
        return false;
    }

    $canmanagecertificate = has_capability('mod/certificate:manage', $context);
    if ($USER->id != $certrecord->userid and !$canmanagecertificate) {
        return false;
    }

    if ($filearea === 'issue') {
        $relativepath = implode('/', $args);
        $fullpath = "/{$context->id}/mod_certificate/issue/$certrecord->id/$relativepath";

        $fs = get_file_storage();
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }
        send_stored_file($file, 0, 0, true); // download MUST be forced - security!
    } else if ($filearea === 'onthefly') {
        require_once($CFG->dirroot.'/mod/certificate/locallib.php');
        require_once("$CFG->libdir/pdflib.php");

        if (!$certificate = $DB->get_record('certificate', array('id' => $certrecord->certificateid))) {
            return false;
        }

        if ($certificate->requiredtime && !$canmanagecertificate) {
            if (certificate_get_course_time($course->id) < ($certificate->requiredtime * 60)) {
                return false;
            }
        }

        // Load the specific certificate type. It will fill the $pdf var.
        require("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");
        $filename = certificate_get_certificate_filename($certificate, $cm, $course) . '.pdf';
        $filecontents = $pdf->Output('', 'S');
        send_file($filecontents, $filename, 0, 0, true, true, 'application/pdf');
    }
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
