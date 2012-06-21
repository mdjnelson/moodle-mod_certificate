<?php

// This file keeps track of upgrades to
// the certificate module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_certificate_upgrade($oldversion=0) {

    global $CFG, $THEME, $DB;
    $dbman = $DB->get_manager();

    // ===== 1.9.0 or older upgrade line ======//
    if ($oldversion < 2007061300) {
        // Add new fields to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('emailothers');
        $field->set_attributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, 'emailteachers');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('certificate');
        $field = new xmldb_field('printhours');
        $field->set_attributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, 'gradefmt');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('certificate');
        $field = new xmldb_field('lockgrade');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'printhours');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('certificate');
        $field = new xmldb_field('requiredgrade');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'lockgrade');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Rename field save to savecert
        $field = new xmldb_field('save');
        if ($dbman->field_exists($table, $field)) {
            $field->set_attributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'emailothers');
            // Launch rename field savecert
            $dbman->rename_field($table, $field, 'savecert');
        } else {
            $field = new xmldb_field('savecert');
            $field->set_attributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'emailothers');

            $dbman->add_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2007061300, 'certificate');
    }

    if ($oldversion < 2007061301) {
        $table = new xmldb_table('certificate_linked_modules');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,  null, null);
        $table->add_field('certificate_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'id');
        $table->add_field('linkid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'certificate_id');
        $table->add_field('linkgrade', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'linkid');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'linkgrade');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'), null, null);
        $table->add_index('certificate_id', XMLDB_INDEX_NOTUNIQUE, array('certificate_id'));
        $table->add_index('linkid', XMLDB_INDEX_NOTUNIQUE, array('linkid'));
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2007061301, 'certificate');
    }

    if ($oldversion < 2007061302) {
        $table = new xmldb_table('certificate_linked_modules');
        $field = new xmldb_field('linkid');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'certificate_id');
        $dbman->change_field_unsigned($table, $field);

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2007061302, 'certificate');
    }

    if ($oldversion < 2007102800) {
        // Add new fields to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('reportcert');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'savecert');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('certificate_issues');
        $field = new xmldb_field('reportgrade');
        $field->set_attributes(XMLDB_TYPE_CHAR, '10', null, null, null, null, 'certdate');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2007102800, 'certificate');
    }

    if ($oldversion < 2007102806) {
        // Add new fields to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('printoutcome');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'gradefmt');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2007102806, 'certificate');
    }

    if ($oldversion < 2008080904) {
        // Add new fields to certificate table if they dont already exist
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('intro');
        $field->set_attributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, 'name');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2008080904, 'certificate');
    }

    //===== 2.0 or older upgrade line ======//

    // Note, fresh 1.9 installs add the version 2009080900, so they miss this when upgrading from 1.9 -> 2.0.
    if ($oldversion < 2009062900) {
        // Add new field to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'intro');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('orientation', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, ' ', 'certificatetype');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('reissuecert', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'reportcert');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Set default orientation accordingly
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'portrait'));
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'letter_portrait'));
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'unicode_portrait'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'landscape'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'letter_landscape'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'unicode_landscape'));

        // Update all the certificate types
        $DB->set_field('certificate', 'certificatetype', 'A4_non_embedded', array('certificatetype' => 'landscape'));
        $DB->set_field('certificate', 'certificatetype', 'A4_non_embedded', array('certificatetype' => 'portrait'));
        $DB->set_field('certificate', 'certificatetype', 'A4_embedded', array('certificatetype' => 'unicode_landscape'));
        $DB->set_field('certificate', 'certificatetype', 'A4_embedded', array('certificatetype' => 'unicode_portrait'));
        $DB->set_field('certificate', 'certificatetype', 'letter_non_embedded', array('certificatetype' => 'letter_landscape'));
        $DB->set_field('certificate', 'certificatetype', 'letter_non_embedded', array('certificatetype' => 'letter_portrait'));

        // savepoint reached
        upgrade_mod_savepoint(true, 2009062900, 'certificate');
    }

    if ($oldversion < 2011030105) {

        // Define field id to be added to certificate
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, 0, 'intro');

        // Conditionally launch add field id
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2011030105, 'certificate');
    }

    // The Moodle 2.0 CVS certificate version sets it to 2011110101, if the user performed an upgrade
    // then this upgrade will take care of several issues, if it's a fresh install then nothing is done.
    if ($oldversion < 2011110102) {
        require_once($CFG->libdir.'/conditionlib.php');

        $table = new xmldb_table('certificate');

        // It is possible for these fields not to be added, ever, it is included in the upgrade
        // process but fresh certificate 1.9 install from CVS MOODLE_19_STABLE set the Moodle version
        // to 2009080900, which means it missed all the earlier code written for upgrading to 2.0.
        $reissuefield = new xmldb_field('reissuecert', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'reportcert');
        $orientationfield = new xmldb_field('orientation', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, ' ', 'certificatetype');

        // Have to check, may be added during earlier upgrade, or may be missing due to not being included in install.xml
        if (!$dbman->field_exists($table, $reissuefield)) {
            $dbman->add_field($table, $reissuefield);
        }

        if (!$dbman->field_exists($table, $orientationfield)) {
            $dbman->add_field($table, $orientationfield);
        }

        // Fresh 2.0 installs won't have this table, but upgrades from 1.9 will
        if ($dbman->table_exists('certificate_linked_modules')) {
            // No longer need lock grade, or required grade, but first need to
            // convert so that the restrictions are still in place for Moodle 2.0
            if ($certs = $DB->get_records('certificate')) {
                foreach ($certs as $cert) {
                    if ($cert->lockgrade == 0) {
                        // Can skip this certificate, no course grade required
                        continue;
                    }
                    if (!$cm = get_coursemodule_from_instance('certificate', $cert->id)) {
                        // Not valid skip it
                        continue;
                    }
                    if (!$gradeitem = $DB->get_record('grade_items', array('courseid' => $cm->course, 'itemtype' => 'course'))) {
                        // Not valid skip it
                        continue;
                    }
                    $condition_info = new condition_info($cm, CONDITION_MISSING_EVERYTHING);
                    $condition_info->add_grade_condition($gradeitem->id, $cert->requiredgrade, '110');
                }
            }
            // Fresh installs won't have this table, but upgrades will
            // Lock grade and required grade field are not needed anymore
            if ($dbman->field_exists($table, 'lockgrade')) {
                $field = new xmldb_field('lockgrade');
                $dbman->drop_field($table, $field);
            }
            if ($dbman->field_exists($table, 'requiredgrade')) {
                $field = new xmldb_field('requiredgrade');
                $dbman->drop_field($table, $field);
            }
            // Now we need to loop through the restrictions in the certificate_linked_modules
            // table and convert it into the new Moodle 2.0 restrictions
            if ($certlinks = $DB->get_records('certificate_linked_modules')) {
                foreach ($certlinks as $link) {
                    // If the link id is '-1' then the setting applies to the time spent in the course and is not
                    // related to a module, meaning we can skip it for this section
                    if ($link->linkid == '-1') {
                        continue;
                    }
                    // Get the course module
                    if (!$cm = get_coursemodule_from_instance('certificate', $link->certificate_id)) {
                        // Not valid skip it
                        continue;
                    }
                    // Get grade item for module specified - is there an API function for this ??
                    $sql = "SELECT gi.id
                            FROM {course_modules} cm
                            INNER JOIN {modules} m
                            ON cm.module = m.id
                            INNER JOIN {grade_items} gi
                            ON m.name = gi.itemmodule
                            WHERE cm.id = :cmid
                            AND cm.course = :courseid
                            AND cm.instance = gi.iteminstance";
                    if (!$gradeitem = $DB->get_record_sql($sql, array('cmid'=>$link->linkid, 'courseid'=>$cm->course))) {
                        // Not valid skip it
                        continue;
                    }
                    $condition_info = new condition_info($cm, CONDITION_MISSING_EVERYTHING);
                    $condition_info->add_grade_condition($gradeitem->id, $link->linkgrade, '110', true);
                }
            }
        }
        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2011110102, 'certificate');
    }

    // Note - the date has not changed as it has been set in the future, so I am incrementing
    // last digits. Actual date - 15/09/11
    if ($oldversion < 2011110103) {
        // New orientation field needs a value in order to view the cert, otherwise you get
        // an issue with FPDF and invalid orientation. This should be done during the upgrade,
        // but due to version number issues it is possible it was not executed, so do it now.
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'portrait'));
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'letter_portrait'));
        $DB->set_field('certificate', 'orientation', 'P', array('certificatetype' => 'unicode_portrait'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'landscape'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'letter_landscape'));
        $DB->set_field('certificate', 'orientation', 'L', array('certificatetype' => 'unicode_landscape'));

        // If the certificate type does not match any of the orientations in the above then set to 'L'
        $sql = "UPDATE {certificate}
                SET orientation = 'L'
                WHERE orientation = ''";
        $DB->execute($sql);

        // Update all the certificate types
        $DB->set_field('certificate', 'certificatetype', 'A4_non_embedded', array('certificatetype' => 'landscape'));
        $DB->set_field('certificate', 'certificatetype', 'A4_non_embedded', array('certificatetype' => 'portrait'));
        $DB->set_field('certificate', 'certificatetype', 'A4_embedded', array('certificatetype' => 'unicode_landscape'));
        $DB->set_field('certificate', 'certificatetype', 'A4_embedded', array('certificatetype' => 'unicode_portrait'));
        $DB->set_field('certificate', 'certificatetype', 'letter_non_embedded', array('certificatetype' => 'letter_landscape'));
        $DB->set_field('certificate', 'certificatetype', 'letter_non_embedded', array('certificatetype' => 'letter_portrait'));

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2011110103, 'certificate');
    }

    if ($oldversion < 2012010501) {
        // Unnecessary length of 2 for fields in the certificate table
        $table = new xmldb_table('certificate');
        // Email teachers
        $field = new xmldb_field('emailteachers');
        $field->type = XMLDB_TYPE_INTEGER;
        $field->length = 1;
        $field->unsigned = true;
        $field->notnull = true;
        $field->default = 0;
        $dbman->change_field_precision($table, $field);
        // Save cert
        $field = new xmldb_field('savecert');
        $field->type = XMLDB_TYPE_INTEGER;
        $field->length = 1;
        $field->unsigned = true;
        $field->notnull = true;
        $field->default = 0;
        $dbman->change_field_precision($table, $field);
        // Report cert
        $field = new xmldb_field('reportcert');
        $field->type = XMLDB_TYPE_INTEGER;
        $field->length = 1;
        $field->unsigned = true;
        $field->notnull = true;
        $field->default = 0;
        $dbman->change_field_precision($table, $field);
        // Reissue cert
        $field = new xmldb_field('reissuecert');
        $field->type = XMLDB_TYPE_INTEGER;
        $field->length = 1;
        $field->unsigned = true;
        $field->notnull = true;
        $field->default = 0;
        $dbman->change_field_precision($table, $field);

        // Increase the length of the studentname field in the issue table - CONTRIB-3324
        $table = new xmldb_table('certificate_issues');
        $field = new xmldb_field('studentname');
        $field->type = XMLDB_TYPE_CHAR;
        $field->length = '255';
        $field->notnull = true;
        $field->default = '';
        $dbman->change_field_precision($table, $field);
        // While we are here change the length of classname to 255, for some reason it is at 254
        $field = new xmldb_field('classname');
        $field->type = XMLDB_TYPE_CHAR;
        $field->length = '255';
        $field->notnull = true;
        $field->default = '';
        $dbman->change_field_precision($table, $field);

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2012010501, 'certificate');
    }

    if ($oldversion < 2012022001) {
        // CONTRIB-3470 - certdate remaining 0 on issued certificates, need to update
        $sql = "UPDATE {certificate_issues}
                SET certdate = timecreated
                WHERE certdate = 0";
        $DB->execute($sql);

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2012022001, 'certificate');
    }

    if ($oldversion < 2012060901) {
        // Editing this table
        $table = new xmldb_table('certificate');

        // Get rid of the reissue cert column, this was a hack introduced later
        // in 1.9 when the bug was brought up that grades were not refreshing
        // since they were being stored in the issues table.
        // The certificate will now always return the current grade, student name
        // and course name.
        $field = new xmldb_field('reissuecert');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // The poor certificate_issues table is going to have a lot of
        // duplicates, we don't need that now, just keep the latest one
        $sql = "DELETE
                FROM {certificate_issues}
                WHERE id NOT IN (SELECT * FROM (SELECT MAX(id)
                                 FROM {certificate_issues}
                                 GROUP BY certificateid, userid) as t)";
        $DB->execute($sql);

        // Going to be editing this table
        $table = new xmldb_table('certificate_issues');

        // Conditionally remove columns no longer needed
        $field = new xmldb_field('studentname');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field = new xmldb_field('classname');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field = new xmldb_field('certdate');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field = new xmldb_field('reportgrade');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field = new xmldb_field('mailed');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2012060901, 'certificate');
    }

    return true;
}
