<?php
/**
 * Provides some custom settings classes for the certificate mod
 * global settings page
 *
 * @author Michael Avelar <michaela@moodlerooms.com>
 * @version $Id$
 * @package mod/certificate
 **/

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

class mod_certificate_admin_setting_configdirectory extends admin_setting_configdirectory {

    public function __construct($name, $visiblename, $description, $defaultdirectory) {
        parent::__construct($name, $visiblename, $description, $defaultdirectory);
        $this->paramtype = PARAM_PATH;
    }

    function write_setting($data) {
        return parent::write_setting($this->prepare_pixpath($data));
    }

    function prepare_pixpath($data) {
        $data = str_replace("\\", "/", $data);
        $data = trim(trim($data, "/"));
        return $data;
    }

    /**
     * Validate data before storage
     * @param string data
     * @return mixed true if ok string if error found
     */
    function validate($data) {
        global $CFG;

        if ($data) {
            $data = $this->prepare_pixpath($data);
            $paths = explode("/", $data);

            if (count($paths) > 1) {
                $rootpath1 = (empty($paths[0])) ? $paths[1] : $paths[0];
            } else {
                $rootpath1 = $data;
            }
            $paths = explode("\\", $data);
            if (count($paths) > 1) {
                $rootpath2 = (empty($paths[0])) ? $paths[1] : $paths[0];
            } else {
                $rootpath2 = $data;
            }
            $fulldir = $CFG->dataroot.'/'.$data;

            // Not an exhaustive list, but prevent nasty
            $reserved = array(
                'archive',
                'block_convert',
                'cache',
                'codecoverage',
                'express',
                'filedir',
                'moodle19to20',
                'sessions',
                'temp',
                'trashdir',
                'upgradelogs',
            );

            // Ensure directory exists, is a directory, and is not the site directory
            if (!in_array($rootpath1, $reserved) and !in_array($rootpath2, $reserved) && (file_exists($fulldir) and is_dir($fulldir))) {
                return true;
            } else {
                return get_string('errcertdir', 'certificate');
            }
        } else {
            // empty value
            return true;
        }
    }

    function output_html($data, $query='') {

        $default = $this->get_defaultsetting();
        if ($data) {
            $data = $this->prepare_pixpath($data);
        } else {
            $data = $default;
        }
        $validated = $this->validate($data);

        if ($validated !== true) {
            $executable = '<span class="patherror">&#x2718;</span>';
        } else {
            $executable = '<span class="pathok">&#x2714;</span>';
        }

        return format_admin_setting($this, $this->visiblename,
                '<div class="form-file defaultsnext"><input type="text" size="'.$this->size.'" id="'.$this->get_id().'" name="'.$this->get_full_name().'" value="'.s($data).'" />'.$executable.'</div>',
                $this->description, true, '', $default, $query);
    }
}
?>