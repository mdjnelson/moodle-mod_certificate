<?php
// This file is part of Moodle - http://moodle.org/
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
 * Mobile output class for certificate
 *
 * @package    mod_certificate
 * @copyright  2018 Juan Leyva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_certificate\output;

defined('MOODLE_INTERNAL') || die();

use context_module;
use mod_certificate_external;

/**
 * Mobile output class for certificate
 *
 * @package    mod_certificate
 * @copyright  2018 Juan Leyva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    /**
     * Returns the certificate course view for the mobile app.
     * @param  array $args Arguments from tool_mobile_get_content WS
     *
     * @return array       HTML, javascript and otherdata
     */
    public static function mobile_course_view($args) {
        global $OUTPUT, $USER, $DB;

        $args = (object) $args;
        $cm = get_coursemodule_from_id('certificate', $args->cmid);

        // Capabilities check.
        require_login($args->courseid , false , $cm, true, true);

        $context = context_module::instance($cm->id);

        require_capability ('mod/certificate:view', $context);
        if ($args->userid != $USER->id) {
            require_capability('mod/certificate:manage', $context);
        }
        $certificate = $DB->get_record('certificate', array('id' => $cm->instance));

        // Get certificates from external (taking care of exceptions).
        try {
            $issued = mod_certificate_external::issue_certificate($cm->instance);
            $certificates = mod_certificate_external::get_issued_certificates($cm->instance);
            $issues = array_values($certificates['issues']); // Make it mustache compatible.
        } catch (Exception $e) {
            $issues = array();
        }

        // Set timemodified for each certificate.
        foreach ($issues as $issue) {
            if (empty($issue->timemodified)) {
                $issue->timemodified = $issue->timecreated;
            }
        }

        $showget = true;
        if ($certificate->requiredtime && !has_capability('mod/certificate:manage', $context)) {
            if (certificate_get_course_time($certificate->course) < ($certificate->requiredtime * 60)) {
                $showget = false;
            }
        }

        $certificate->name = format_string($certificate->name);
        list($certificate->intro, $certificate->introformat) =
                        external_format_text($certificate->intro, $certificate->introformat, $context->id,
                                                'mod_certificate', 'intro');
        $data = array(
            'certificate' => $certificate,
            'showget' => $showget && count($issues) > 0,
            'issues' => $issues,
            'issue' => $issues[0],
            'numissues' => count($issues),
            'cmid' => $cm->id,
            'courseid' => $args->courseid
        );

        return array(
            'templates' => array(
                array(
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_certificate/mobile_view_page', $data),
                ),
            ),
            'javascript' => '',
            'otherdata' => '',
            'files' => $issues
        );
    }

    /**
     * Returns the certificate issues view for the mobile app.
     * @param  array $args Arguments from tool_mobile_get_content WS
     *
     * @return array       HTML, javascript and otherdata
     */
    public static function mobile_issues_view($args) {
        global $OUTPUT, $USER, $DB;

        $args = (object) $args;
        $cm = get_coursemodule_from_id('certificate', $args->cmid);

        // Capabilities check.
        require_login($args->courseid , false , $cm, true, true);

        $context = context_module::instance($cm->id);

        require_capability ('mod/certificate:view', $context);
        if ($args->userid != $USER->id) {
            require_capability('mod/certificate:manage', $context);
        }
        $certificate = $DB->get_record('certificate', array('id' => $cm->instance));

        // Get certificates from external (taking care of exceptions).
        try {
            $issued = mod_certificate_external::issue_certificate($cm->instance);
            $certificates = mod_certificate_external::get_issued_certificates($cm->instance);
            $issues = array_values($certificates['issues']); // Make it mustache compatible.
        } catch (Exception $e) {
            $issues = array();
        }

        $data = array(
            'issues' => $issues
        );

        return array(
            'templates' => array(
                array(
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_certificate/mobile_view_issues', $data),
                ),
            ),
            'javascript' => '',
            'otherdata' => ''
        );
    }
}
