<?php
/**
 * Class handles this modules handling of default/custom pixs locations,
 * folders, files, validation, etc.  For use in generating PDF's via
 * Certificate Module.
 *
 * @author Michael Avelar <michaela@moodlerooms.com>
 * @version $Id$
 * @package mod/certificate
 **/

class mod_certificate_pix_handler {
    /**
     * All picture paths under the set directory which can contain images for
     * selection.  All are optional.
     *
     * @access public
     * @var array
     */
    public static $pixpaths = array('borders', 'seals',
                                    'signatures', 'watermarks');
    /**
     * Image types supported by the FPDF Lib
     *
     * @access public
     * @var array
     **/
    public static $supportedtypes = array('jpe' => 'image/jpeg',
                                            'jpeIE' => 'image/pjpeg',
                                            'jpeg' => 'image/jpeg',
                                            'jpegIE' => 'image/pjpeg',
                                            'jpg' => 'image/jpeg',
                                            'jpgIE' => 'image/pjpeg',
                                            'png'  => 'image/png',
                                            'pngIE' => 'image/x-png');

    /**
     * Default mod directory created on upgrade in data directory.
     *
     * @access protected
     * @var string
     */
    protected static $moddir   = 'mod_certificate';

    /**
     * Default mod directory for pixpath before upgrade.
     *
     * @access protected
     * @var string
     */
    protected static $defaultmoddir = 'mod/certificate/pix';

    /**
     * Globally set config variable toggling alternate
     * directory settings or not.
     *
     * @access protected
     * @var boolean
     */
    protected static $enable_altdir;

    /**
     * Globally set config variable for selected alternate directory
     *
     * @access protected
     * @var string/path
     */
    protected static $cert_dir;

    /**
     * Constructor function
     */
    public function mod_certificate_pix_handler() {

    }

    /**
     * Creates default dynamic alternate picture location dirs
     *
     * @return bool
     */
    public static function create_default_alternate_pixpath() {
        global $CFG;
        $moddir     = mod_certificate_pix_handler::get_default_cert_dir();
        $pixpaths   = mod_certificate_pix_handler::get_pixpaths();
        $result     = true;
        $moddir     = "$CFG->dataroot/$moddir";

        if (!check_dir_exists($moddir)) {
            $result = false;
        } else {
            foreach ($pixpaths as $pp) {
                if (!check_dir_exists($moddir.'/'.$pp)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * Returns the default alternate certificate pix path.
     *
     * @return string Partial dir
     */
    public static function get_default_cert_dir() {
        return self::$moddir;
    }

    /**
     * Returns static array of optional pixpaths for selection.
     *
     * @return array
     */
    public static function get_pixpaths() {
        return self::$pixpaths;
    }

    /**
     * Returns static string containing currently selected pix path
     *
     * @param boolean $default          always return default
     * @param boolean $returnpartial    if true, returns directory from dataroot
     * @return string
     */
    public static function get_certmod_pixpath($returnpartial = false) {
        global $CFG;

        if (!isset(self::$cert_dir)) {
            self::$cert_dir = get_config('mod_certificate', 'certificate_directory');
        }
        if (!empty(self::$cert_dir)) {
            if ($returnpartial) {
                return self::$cert_dir;
            }
            return $CFG->dataroot.'/'.self::$cert_dir;
        }
        if ($returnpartial) {
            return '';
        }
        return $CFG->dataroot;
    }

    /**
     * Returns default mod directory.
     *
     * @param boolean $returnpartial    if true, returns directory from dataroot
     * @return string
     */
    public static function get_default_moddir($returnpartial = false) {
        global $CFG;
        if ($returnpartial) {
            return self::$defaultmoddir;
        }
        return $CFG->dirroot.'/'.self::$defaultmoddir;
    }

    /**
     * Returns an array with all the filenames in
     * all subdirectories, relative to the given rootdir.
     * All files are excluded excepting those in the $supportedtypes array.
     *
     * @param string $rootdir   Either path to pix directory or entire path
     * @param bool   $default   Returns dir contents from default custom directory.
     * @return array An array with all the filenames in all subdirectories,
     *                  relative to the given rootdir.
     */
    public static function get_valid_dir_contents($pixpath, $pixdir) {
        global $CFG;

        $fulldir = $pixdir.'/'.$pixpath;

        $dirs       = array();

        if (!is_dir($fulldir)) {          // Must be a directory
            return $dirs;
        }

        if (!$dir = opendir($fulldir)) {  // Can't open it for some reason
            return $dirs;
        }

        while (false !== ($file = readdir($dir))) {
            $firstchar = substr($file, 0, 1);
            if ($firstchar == '.' or $file == 'CVS') {
                continue;
            }

            $fullpath = $fulldir.'/'.$file;
            if (filetype($fullpath) == 'dir') {
                $subdirs = self::get_valid_dir_contents($pixpath.'/'.$file, $pixdir);

                foreach ($subdirs as $subdir) {
                    $dirs[($file.'/'.$subdir)] = $file .'/'. $subdir;
                }
            } else {
                // Do not add files not in supported types
                $ext = explode ( '.', $file );
                if (!array_key_exists(strtolower(array_pop($ext)), self::$supportedtypes)) {
                    continue;
                }
                $dirs[$file] = $file;
            }
        }
        closedir($dir);
        asort($dirs);

        return $dirs;
    }

    /**
     * Returns an array with all the filenames in all directories and
     * all subdirectories in both the custom pixpath and the default pixpath.
     * All files are excluded excepting those in the $supportedtypes array.
     *
     * @param string $pixpath   Subfolder specifying type of image
     * @return array An array with all the filenames in all subdirectories,
     *                  relative to the given rootdir.
     */
    public static function get_all_valid_dir_contents($pixpath) {

        $custompix = self::get_valid_dir_contents($pixpath, self::get_certmod_pixpath());

        $defaultpix = self::get_valid_dir_contents($pixpath, self::get_default_moddir());

        return $custompix + $defaultpix;
    }

    /**
     * Returns entire path to the primary directory of selected picture.
     *
     * @param string $type      Subfolder specifying type of image.
     * @param string $picname   Actual name of the selected picture.
     * @return string   Full path to the primary directory of selected picture.
     */
    public static function get_selected_pic($type, $picname) {
        $my_path    = mod_certificate_pix_handler::get_certmod_pixpath();
        if (!file_exists("$my_path/$type/$picname")) {
            $my_path = mod_certificate_pix_handler::get_default_moddir();
            if (!file_exists("$my_path/$type/$picname")) {
                $my_path = NULL;
            }
        }
        return $my_path;
    }
}
?>
