<?php
/**
 * Provides some custom settings for the certificate module
 *
 * @author Michael Avelar <michaela@moodlerooms.com>
 * @version $Id$
 * @package mod/certificate
 **/
require_once($CFG->dirroot.'/mod/certificate/adminsetting.class.php');
require_once($CFG->dirroot.'/mod/certificate/certpixhandler.class.php');

// Attempt to create default directory if does not exist
$result     = mod_certificate_pix_handler::create_default_alternate_pixpath();
$subdir     = mod_certificate_pix_handler::get_default_cert_dir();
$pixpaths   = mod_certificate_pix_handler::get_pixpaths();
$configs    = array();

$configs[] = new mod_certificate_admin_setting_configdirectory('certificate_directory', get_string('pixpath', 'certificate'), get_string('pixpathdesc', 'certificate'), $subdir);

foreach ($pixpaths as $pp) {
    $options = array('0' => get_string('none'));
    $options += mod_certificate_pix_handler::get_all_valid_dir_contents($pp);
    
    $configs[] = new admin_setting_configselect('certificate_'.$pp, get_string('pixpath_'.$pp, 'certificate'), get_string('pixpath_pathdesc', 'certificate', $subdir.'/'.$pp), '0', $options);
}

$configs[] = new mod_certificate_admin_setting_upload('certificate_uploadpic', get_string('uploadpic', 'certificate'), get_string('uploadpicdesc', 'certificate'), '');

// Define the config plugin so it is saved to
// the config_plugin table then add to the settings page
foreach ($configs as $config) {
    $config->plugin = 'mod_certificate';
    $settings->add($config);
}

?>