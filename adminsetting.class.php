<?php

/**
 * Creates an upload form on the settings page
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Michael Avelar <michaela@moodlerooms.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/adminlib.php');

/**
* Class extends admin setting class to allow/process an uploaded file
**/
class mod_certificate_admin_setting_upload extends admin_setting_configtext {
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, PARAM_RAW, 50);
    }

    function output_html($data, $query='') {
        // Create a dummy var for this field
        $this->config_write($this->name, '');

        return format_admin_setting($this, $this->visiblename,
                html_writer::link(new moodle_url('/mod/certificate/upload_image.php'), get_string('upload')),
                $this->description, true, '', null, $query);
    }
}

?>