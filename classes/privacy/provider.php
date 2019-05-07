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
 * Privacy subsystem implementation for mod_certificate.
 *
 * @package mod_certificate
 * @copyright 2018 Huong Nguyen <huongnv13@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_certificate\privacy;

use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\userlist;

defined('MOODLE_INTERNAL') || die();

/**
 * Implementation of the privacy subsystem plugin provider for the mod_certificate.
 *
 * @package mod_certificate
 * @copyright 2018 Huong Nguyen <huongnv13@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\core_userlist_provider {

    /**
     * Returns meta data about this system.
     *
     * @param collection $items The initialised collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items): collection {
        $items->add_database_table('certificate_issues', [
                'userid' => 'privacy:metadata:mod_certificate:userid',
                'certificateid' => 'privacy:metadata:mod_certificate:certificateid',
                'code' => 'privacy:metadata:mod_certificate:code',
                'timecreated' => 'privacy:metadata:mod_certificate:timecreated',
        ], 'privacy:metadata:mod_certificate');

        $items->link_subsystem('core_files', 'privacy:metadata:core_files');

        return $items;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {certificate_issues} ci ON ci.certificateid = cm.instance
                  JOIN {user} u ON u.id = ci.userid
                 WHERE u.id = :userid";

        $params = [
                'modname' => 'certificate',
                'contextlevel' => CONTEXT_MODULE,
                'userid' => $userid
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        if (!$context instanceof \context_module) {
            return;
        }
        // Fetch all users who have a certificate.
        $sql = "SELECT ci.userid
                  FROM {course_modules} cm
                  JOIN {modules} m
                    ON m.id = cm.module AND m.name = :modname
                  JOIN {certificate} cer
                    ON cer.id = cm.instance
                  JOIN {certificate_issues} ci
                    ON ci.certificateid = cer.id
                 WHERE cm.id = :cmid";
        $params = [
                'cmid'      => $context->instanceid,
                'modname'   => 'certificate',
        ];
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist)) {
            return;
        }

        $user = $contextlist->get_user();
        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);

        $sql = "SELECT c.id as contextid,
                       ci.*
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {certificate_issues} ci ON ci.certificateid = cm.instance
                  JOIN {user} u ON u.id = ci.userid
                 WHERE u.id = :userid
                       AND (c.id {$contextsql})";

        $params = [
                'modname' => 'certificate',
                'contextlevel' => CONTEXT_MODULE,
                'userid' => $user->id,
        ];

        $params += $contextparams;

        $certificateissues = $DB->get_recordset_sql($sql, $params);

        foreach ($certificateissues as $c) {
            $context = context::instance_by_id($c->contextid);
            $cidata = (object) [
                    'userid' => transform::user($c->userid),
                    'certificateid' => $c->certificateid,
                    'code' => $c->code,
                    'timecreated' => transform::datetime($c->timecreated),
            ];
            $area = [$c->id];
            writer::with_context($context)->export_data($area, $cidata);
            writer::with_context($context)->export_area_files($area, 'mod_certificate', 'issue', $c->id);
        }

        $certificateissues->close();
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        global $DB;

        $sql = "SELECT ci.certificateid
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {certificate_issues} ci ON ci.certificateid = cm.instance
                 WHERE c.id = :contextid";

        $params = [
                'modname' => 'certificate',
                'contextlevel' => CONTEXT_MODULE,
                'contextid' => $context->id
        ];

        $records = $DB->get_records_sql($sql, $params);
        if ($records) {
            foreach ($records as $r) {
                $DB->delete_records('certificate_issues', ['certificateid' => $r->certificateid]);
            }
        }
        $fs = get_file_storage();
        $fs->delete_area_files($context->id, 'mod_certificate', 'issue');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();
        $fs = get_file_storage();

        foreach ($contextlist as $context) {
            $sql = "SELECT ci.certificateid, ci.id
                      FROM {context} c
                      JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                      JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                      JOIN {certificate_issues} ci ON ci.certificateid = cm.instance
                      JOIN {user} u ON u.id = ci.userid
                     WHERE u.id = :userid AND c.id = :modcontextid";
            $params = [
                    'modname' => 'certificate',
                    'contextlevel' => CONTEXT_MODULE,
                    'userid' => $user->id,
                    'modcontextid' => $context->id
            ];

            $records = $DB->get_records_sql($sql, $params);
            if ($records) {
                foreach ($records as $r) {
                    $DB->delete_records('certificate_issues', ['certificateid' => $r->certificateid, 'userid' => $user->id]);
                    $fs->delete_area_files($context->id, 'mod_certificate', 'issue', $r->id);
                }
            }
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();
        if (!$context instanceof \context_module) {
            return;
        }
        $cm = get_coursemodule_from_id('certificate', $context->instanceid);
        if (!$cm) {
            // Only certificate module will be handled.
            return;
        }
        $userids = $userlist->get_userids();
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $select = "certificateid = :certificateid AND userid $usersql";
        $params = ['certificateid' => $cm->instance] + $userparams;
        $DB->delete_records_select('certificate_issues', $select, $params);
    }
}
