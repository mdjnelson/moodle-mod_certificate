<?php

/**
 * Provides some custom settings for the certificate module
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Michael Avelar <michaela@moodlerooms.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/mod/certificate/adminsetting.class.php');

$settings->add(new mod_certificate_admin_setting_upload('certificate/uploadimage',
    get_string('uploadimage', 'certificate'), get_string('uploadimagedesc', 'certificate'), ''));

?>