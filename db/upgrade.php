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

    if ($result && $oldversion < 2009062900) {

    /// Add new field to certificate table
        $table = new xmldb_table('certificate');
        $field = new XMLDBField('customtext', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'printteacher');
        $field = new xmldb_field('orientation', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, '0', 'certificatetype');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'intro');
        $dbman->add_field($table, $field);

    /// Set default orientation accordingly
        $sql1 = "UPDATE {certificate} SET orientation='P' WHERE certificatetype='portrait'";
        $result = $DB->execute($sql1);
        $sql2 = "UPDATE {certificate} SET orientation='P' WHERE certificatetype='letter_portrait'";
        $result = $DB->execute($sql2);
        $sql3 = "UPDATE {certificate} SET orientation='P' WHERE certificatetype='unicode_portrait'";
        $result = $DB->execute($sql3);
        $sql4 = "UPDATE {certificate} SET orientation='L' WHERE certificatetype='landscape'";
        $result = $DB->execute($sql4);
        $sql5 = "UPDATE {certificate} SET orientation='L' WHERE certificatetype='letter_landscape'";
        $result = $DB->execute($sql5);
        $sql6 = "UPDATE {certificate} SET orientation='L' WHERE certificatetype='unicode_landscape'";
        $result = $DB->execute($sql6);

        // Update all the certificate types
        $sql12 = "UPDATE {certificate} SET certificatetype='A4_non_embedded' WHERE certificatetype='landscape'";
        $result = $DB->execute($sql12);
        $sql22 = "UPDATE {certificate} SET certificatetype='A4_non_embedded' WHERE certificatetype='portrait'";
        $result = $DB->execute($sql22);
        $sql32 = "UPDATE {certificate} SET certificatetype='A4_embedded' WHERE certificatetype='unicode_landscape'";
        $result = $DB->execute($sql32);
        $sql42 = "UPDATE {certificate} SET certificatetype='A4_embedded' WHERE certificatetype='unicode_portrait'";
        $result = $DB->execute($sql42);
        $sql52 = "UPDATE {certificate} SET certificatetype='letter_non_embedded' WHERE certificatetype='letter_landscape'";
        $result = $DB->execute($sql52);
        $sql62 = "UPDATE {certificate} SET certificatetype='letter_non_embedded' WHERE certificatetype='letter_portrait'";
        $result = $DB->execute($sql62);

    /// Add new field to certificate table
        $table = new xmldb_table('certificate');
        $field = new xmldb_field('reissuecert', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'reportcert');
        $dbman->add_field($table, $field);

    /// savepoint reached
        upgrade_mod_savepoint($result, 2009062900, 'certificate');
    }


//===== 1.9.0 or older upgrade line ======//

    if ($result && $oldversion < 2007102806) {
    /// Add new fields to certificate table

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('printoutcome');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'gradefmt');
        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2007102800) {
    /// Add new fields to certificate table

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
    /// Add new fields to certificate table

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

    /// Rename field save to savecert
        $field = new XMLDBField('save');
        if (field_exists($table, $field)) {
            $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'emailothers');

        /// Launch rename field savecert
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
    /// Add new fields to certificate table if they dont already exist

        $table = new XMLDBTable('certificate');
        $field = new XMLDBField('intro');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'small', null, null, null, null, null, null, 'name');
        if (!field_exists($table, $field)) {
            $result = $result && add_field($table, $field);
        }
    }
    return $result;
}

?>