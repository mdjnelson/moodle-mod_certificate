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
 * Handles viewing the report
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id   = required_param('id', PARAM_INT); // Course module ID.
$sort = optional_param('sort', '', PARAM_RAW);
$download = optional_param('download', '', PARAM_ALPHA);
$action = optional_param('action', '', PARAM_ALPHA);

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', CERT_PER_PAGE, PARAM_INT);

// Ensure the perpage variable does not exceed the max allowed if
// the user has not specified they wish to view all certificates.
if (CERT_PER_PAGE !== 0) {
    if (($perpage > CERT_MAX_PER_PAGE) || ($perpage <= 0)) {
        $perpage = CERT_MAX_PER_PAGE;
    }
} else {
    $perpage = '9999999';
}

$url = new moodle_url('/mod/certificate/report.php', array('id' => $id, 'page' => $page, 'perpage' => $perpage));
if ($download) {
    $url->param('download', $download);
}
if ($action) {
    $url->param('action', $action);
}
$PAGE->set_url($url);

if (!$cm = get_coursemodule_from_id('certificate', $id)) {
    print_error('Course Module ID was incorrect');
}

if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('Course is misconfigured');
}

if (!$certificate = $DB->get_record('certificate', array('id' => $cm->instance))) {
    print_error('Certificate ID was incorrect');
}

// Requires a course login.
require_course_login($course->id, false, $cm);

// Check capabilities.
$context = context_module::instance($cm->id);
require_capability('mod/certificate:manage', $context);


// Declare some variables.
// TODO: Following 2 variables are not used, delete them (after asking Mark).
$strcertificates = get_string('modulenameplural', 'certificate');
$strcertificate  = get_string('modulename', 'certificate');

$strreport = get_string('report', 'certificate');

// User information headings.
$ustr = array(get_string('awardedto', 'certificate'), get_string('idnumber'));

// Get users extra field from the settings in /admin/settings.php?section=userpolicies under Show user identity.
if ($extrauserfields = get_extra_user_fields($context, array('idnumber'))) {
    foreach ($extrauserfields as $euf) {
        $ustr[] = get_user_field_name($euf);
    }
}

// Certificate headings.
$cstr = array(get_string('receiveddate', 'certificate'));
if ($certificate->printgrade) {
    $cstr[] = get_string('grade', 'certificate');
}
$cstr[] = get_string('code', 'certificate');

if (!$download) {
    $PAGE->navbar->add($strreport);
    $PAGE->set_title(format_string($certificate->name).": $strreport");
    $PAGE->set_heading($course->fullname);
    // Check to see if groups are being used in this choice.
    if ($groupmode = groups_get_activity_groupmode($cm)) {
        groups_get_activity_group($cm, true);
    }
} else {
    $groupmode = groups_get_activity_groupmode($cm);
    // Get all results when $page and $perpage are 0.
    $page = $perpage = 0;
}

add_to_log($course->id, 'certificate', 'view', "report.php?id=$cm->id", '$certificate->id', $cm->id);

// Ensure there are issues to display, if not display notice.
if (!$users = certificate_get_issues($certificate->id, $DB->sql_fullname(), $groupmode, $cm, $page, $perpage)) {
    echo $OUTPUT->header();
    groups_print_activity_menu($cm, $CFG->wwwroot . '/mod/certificate/report.php?id='.$id);
    notify(get_string('nocertificatesissued', 'certificate'));
    echo $OUTPUT->footer($course);
    exit();
}

if ($download == "ods") {
    require_once("$CFG->libdir/odslib.class.php");

    // Calculate file name.
    $filename = clean_filename("$course->shortname " . rtrim($certificate->name, '.') . '.ods');

    // Creating a workbook.
    $workbook = new MoodleODSWorkbook("-");

    // Create spreadsheet.
    certificate_report_make_spreadsheet(
            $course, $certificate, $users, $context, $extrauserfields, $filename, $workbook, $strreport, $ustr, $cstr);
    exit;
}

if ($download == "xls") {
    require_once("$CFG->libdir/excellib.class.php");

    // Calculate file name.
    $filename = clean_filename("$course->shortname " . rtrim($certificate->name, '.') . '.xls');

    // Creating a workbook.
    $workbook = new MoodleExcelWorkbook("-");

    // Create spreadsheet.
    certificate_report_make_spreadsheet(
            $course, $certificate, $users, $context, $extrauserfields, $filename, $workbook, $strreport, $ustr, $cstr);
    exit;
}

if ($download == "txt") {
    $filename = clean_filename("$course->shortname " . rtrim($certificate->name, '.') . '.txt');

    header("Content-Type: application/download\n");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Expires: 0");
    header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
    header("Pragma: public");

    // Print names of all the fields.
    $username = array(get_string("lastname"), get_string("firstname"));
    $ustr = array_merge($username, array_splice($ustr, 1));
    foreach ($ustr as $us) {
        echo $us . "\t";
    }
    echo get_string("group") . "\t";

    $index = 1;
    $lastcolumn = count($cstr);
    foreach ($cstr as $cs) {
        echo $cs;
        echo ($index++ === $lastcolumn) ? "\n" : "\t";
    }

    // Generate the data for the body of the spreadsheet.
    $row = 1;
    if ($users) {
        foreach ($users as $user) {
            list($udata, $cdata) = certificate_report_get_user_data($course, $certificate, $context, $user, $extrauserfields, true);
            foreach ($udata as $ud) {
                echo $ud . "\t";
            }
            $ug2 = '';
            if ($usergrps = groups_get_all_groups($course->id, $user->id)) {
                foreach ($usergrps as $ug) {
                    $ug2 = $ug2 . $ug->name;
                }
            }
            echo $ug2 . "\t";

            $index = 1;
            $lastcolumn = count($cstr);
            foreach ($cdata as $cd) {
                echo $cd;
                echo ($index++ === $lastcolumn) ? "\n" : "\t";
            }
            $row++;
        }
    }
    exit;
}

$usercount = count(certificate_get_issues($certificate->id, $DB->sql_fullname(), $groupmode, $cm));

echo $OUTPUT->header();
groups_print_activity_menu($cm, $CFG->wwwroot . '/mod/certificate/report.php?id='.$id);
echo $OUTPUT->heading(get_string('modulenameplural', 'certificate'));
echo $OUTPUT->paging_bar($usercount, $page, $perpage, $url);
echo '<br />';

// Set up the table and table options.
require_once($CFG->libdir.'/tablelib.php');
$table = new flexible_table('mod-certificate-report');
$table->set_attribute('id', 'certificates');

// Set base url.
$table->define_baseurl(new moodle_url($CFG->wwwroot.'/mod/certificate/report.php', array('id' => $cm->id)));

// Add additional headers.
$cols = array_merge($ustr, $cstr);
$table->define_columns($cols);

// Set columns for heading.
$table->define_headers($cols);

// Set up the table.
$table->setup();

foreach ($users as $user) {
    list($udata, $cdata) = certificate_report_get_user_data($course, $certificate, $context, $user, $extrauserfields);
    $table->add_data(array_merge($udata, $cdata));
}

// Create table to store buttons.
$tablebutton = new html_table();
$tablebutton->attributes['class'] = 'downloadreport';
$btndownloadods = $OUTPUT->single_button(new moodle_url(
        "report.php", array('id' => $cm->id, 'download' => 'ods')), get_string("downloadods"));
$btndownloadxls = $OUTPUT->single_button(new moodle_url(
        "report.php", array('id' => $cm->id, 'download' => 'xls')), get_string("downloadexcel"));
$btndownloadtxt = $OUTPUT->single_button(new moodle_url(
        "report.php", array('id' => $cm->id, 'download' => 'txt')), get_string("downloadtext"));
$tablebutton->data[] = array($btndownloadods, $btndownloadxls, $btndownloadtxt);

echo html_writer::tag('div', html_writer::table($tablebutton), array('style' => 'margin:auto; width:50%'));
echo $OUTPUT->footer($course);


/**
 * Creates a spreadsheet in a given format
 * @param object $course
 * @param object $certificate
 * @param object $users
 * @param object $context
 * @param object $extrauserfields
 * @param string $filename
 * @param object $workbook
 * @param string $strreport
 * @param object $ustr
 * @param object $cstr
 */
function certificate_report_make_spreadsheet(
        $course, $certificate, $users, $context, $extrauserfields, $filename, $workbook, $strreport, $ustr, $cstr) {
    // Send HTTP headers.
    $workbook->send($filename);
    // Creating the first worksheet.
    $myxls = $workbook->add_worksheet($strreport);

    $username = array(get_string("lastname"), get_string("firstname"));
    $ustr = array_merge($username, array_splice($ustr, 1));
    // Print names of all the fields.
    $col = 0;
    foreach ($ustr as $us) {
        $myxls->write_string(0, $col++, $us);
    }
    $myxls->write_string(0, $col++, get_string("group"));

    foreach ($cstr as $cs) {
        $myxls->write_string(0, $col++, $cs);
    }

    // Generate the data for the body of the spreadsheet.
    $i = 0;
    $row = 1;
    if ($users) {
        foreach ($users as $user) {
            list($udata, $cdata) = certificate_report_get_user_data($course, $certificate, $context, $user, $extrauserfields, true);
            $col = 0;
            foreach ($udata as $ud) {
                $myxls->write_string($row, $col++, $ud);
            }
            $ug2 = '';
            if ($usergrps = groups_get_all_groups($course->id, $user->id)) {
                foreach ($usergrps as $ug) {
                    $ug2 = $ug2 . $ug->name;
                }
            }
            $myxls->write_string($row, $col++, $ug2);
            foreach ($cdata as $cd) {
                $myxls->write_string($row, $col++, $cd);
            }
            $row++;
        }
        $pos = 6;
    }
    // Close the workbook.
    $workbook->close();
}

/**
 * Return user data in two different arrays, userinfo and certificate info
 * @param object $course
 * @param object $certificate
 * @param object $context
 * @param object $user
 * @param object $extrauserfields
 * @param boolean $downloading
 * @return an array object which contains two arrays
 */
function certificate_report_get_user_data($course, $certificate, $context, $user, $extrauserfields, $downloading = false) {
    global $OUTPUT;
    if ($downloading) {
        $name = array($user->lastname, $user->firstname);
    } else {
        $name = array($OUTPUT->user_picture($user) . fullname($user));
    }
    $udata = $name;
    $udata[] = (!empty($user->idnumber)) ? $user->idnumber : " ";

    // Get extra user settings and add it to the userinfo.
    foreach ($extrauserfields as $euf) {
        if (!$user->$euf) {
            $udata[] = '';
        } else {
            $udata[] = $user->$euf;
        }
    }

    $cdata = array();
    if ($downloading) {
        $cdata[] = userdate($user->timecreated);
    } else {
        $cdata[] = userdate($user->timecreated) . certificate_print_user_files($certificate, $user->id, $context->id);
    }
    if ($certificate->printgrade) {
        $cdata[] = certificate_get_grade($certificate, $course, $user->id);
    }
    $cdata[] = $user->code;

    return array($udata, $cdata);
}