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

    $result = true;

    //===== 1.9.0 or older upgrade line ======//
    if ($result && $oldversion < 2007102806) {
        // Add new fields to certificate table
        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('printoutcome');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'gradefmt');
        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2007102800) {
        // Add new fields to certificate table
        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('reportcert');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'savecert');
        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('certificate_issues');
        $field = new XMLDBField('reportgrade');
        $field->setAttributes(XMLDB_TYPE_CHAR, '10', null, null, null, null, null, null, 'certdate');
        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2007061300) {
        // Add new fields to certificate table
        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('emailothers');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'emailteachers');
        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('printhours');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'gradefmt');
        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('lockgrade');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'printhours');
        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('requiredgrade');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'lockgrade');
        $result = $result && add_field($table, $field);

        // Rename field save to savecert
        $field = new XMLDBField('save');
        if (field_exists($table, $field)) {
            $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'emailothers');
            // Launch rename field savecert
            $result = $result && rename_field($table, $field, 'savecert');
        } else {
            $field = new XMLDBField('savecert');
            $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'emailothers');

            $result = $result && add_field($table, $field);
        }

    }

    if ($result && $oldversion < 2007061301) {
        $table = new XMLDBTable('certificate_linked_modules');
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null, null);
        $table->addFieldInfo('certificate_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'id');
        $table->addFieldInfo('linkid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'certificate_id');
        $table->addFieldInfo('linkgrade', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'linkid');
        $table->addFieldInfo('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'linkgrade');
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->addIndexInfo('certificate_id', XMLDB_INDEX_NOTUNIQUE, array('certificate_id'));
        $table->addIndexInfo('linkid', XMLDB_INDEX_NOTUNIQUE, array('linkid'));
        $result = create_table($table);

        if ($result) {
            require_once($CFG->dirroot.'/mod/certificate/lib.php');
            $result = certificate_upgrade_grading_info();
        }
    }

    if ($result && $oldversion < 2007061302) {
        $table  = new XMLDBTable('certificate_linked_modules');
        $field = new XMLDBField('linkid');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, null, '0', 'certificate_id');
        $result = change_field_unsigned($table, $field);
    }

    if ($result && $oldversion < 2008080904) {
        // Add new fields to certificate table if they dont already exist

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('intro');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'name');
        if (!field_exists($table, $field)) {
            $result = $result && add_field($table, $field);
        }
    }

    //===== 2.0 or older upgrade line ======//

    if ($result && $oldversion < 2009062900) {

        // Add new field to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'intro');
        $dbman->add_field($table, $field);

        $field = new xmldb_field('orientation', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, ' ', 'certificatetype');
        $dbman->add_field($table, $field);

        $field = new xmldb_field('reissuecert', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'reportcert');
        $dbman->add_field($table, $field);

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
        upgrade_mod_savepoint($result, 2009062900, 'certificate');
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
    
    if ($oldversion < 2011110101) {
        require_once($CFG->libdir.'/conditionlib.php');
        
        $table = new xmldb_table('certificate');
        
        // It is possible for these fields not to be added, ever, it is included in the upgrade 
        // process but NOT the Moodle 19 stable install.xml, so fresh installs miss them !!
        $reissuefield = new xmldb_field('reissuecert', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'reportcert');
        $orientationfield = new xmldb_field('orientation', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, ' ', 'certificatetype');
        
        // Have to check, may be added during earlier upgrade, or may be missing due to not being included in install.xml
        if (!$dbman->field_exists($table, $reissuefield)) {
            $dbman->add_field($table, $reissuefield);
        }
        
        if (!$dbman->field_exists($table, $orientationfield)) {
            $dbman->add_field($table, $orientationfield);
        }
        
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
                $condition_info->add_grade_condition($gradeitem->id, $cert->requiredgrade, '100');
            }
        }
        // Lock grade and required grade field are not needed anymore
        $field = new xmldb_field('lockgrade');
        $dbman->drop_field($table, $field);
        $field = new xmldb_field('requiredgrade');
        $dbman->drop_field($table, $field);
        
        // Now we need to loop through the restrictions in the certificate_linked_modules 
        // table and convert it into the new Moodle 2.0 restrictions
        if ($certlinks = $DB->get_records('certificate_linked_modules')) {
            foreach ($certlinks as $link) {
                // Get the course module
                if (!$cm = get_coursemodule_from_instance('certificate', $link->certificate_id)) {
                    // Not valid skip it
                    continue;
                }
                // Get grade item for module specified - is there an API function for this ??
                $sql = "SELECT gi.id " .
                       "FROM {course_modules} cm " .
                       "INNER JOIN {modules} m " .
                       "ON cm.module = m.id " .
                       "INNER JOIN {grade_items} gi " .
                       "ON m.name = gi.itemmodule " .
                       "WHERE cm.id = '$link->linkid' " .
                       "AND cm.course = '$cm->course' " .
                       "AND cm.instance = gi.iteminstance";
                if (!$gradeitem = $DB->get_record_sql($sql)) {
                    // Not valid skip it
                    continue;
                }
                $condition_info = new condition_info($cm, CONDITION_MISSING_EVERYTHING);
                $condition_info->add_grade_condition($gradeitem->id, $link->linkgrade, '100');
            }
        }
        // Need to do this so the new conditions are shown when viewing a course
        rebuild_course_cache();
        // Table no longer needed
        $table = new xmldb_table('certificate_linked_modules');
        $dbman->drop_table($table);
        
        // certificate savepoint reached
        upgrade_mod_savepoint(true, 2011110101, 'certificate');
    }


    return $result;
}

?>