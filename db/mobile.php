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
 * Certificate module capability definition
 *
 * @package    mod_certificate
 * @copyright  2016 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$addons = array(
    "mod_certificate" => array(
        "handlers" => array( // Different places where the add-on will display content.
            'coursecertificate' => array( // Handler unique name (can be anything)
                'displaydata' => array(
                    'title' => 'pluginname',
                    'icon' => $CFG->wwwroot . '/mod/certificate/pix/icon.gif',
                    'class' => '',
                ),
                'delegate' => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the add-on)
                'method' => 'mobile_course_view', // Main function in \mod_certificate\output\mobile
                'offlinefunctions' => array(
                    'mobile_course_view' => array(),
                    'mobile_issues_view' => array(),
                ), // Function needs caching for offline.
            )
        ),
        'lang' => array(
            array('pluginname', 'certificate'),
            array('summaryofattempts', 'certificate'),
            array('getcertificate', 'certificate'),
            array('requiredtimenotmet', 'certificate'),
            array('viewcertificateviews', 'certificate')
        )
    )
);
