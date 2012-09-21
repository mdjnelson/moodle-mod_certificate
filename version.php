<?php

/**
 * Code fragment to define the version of the certificate module
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or late
 */

$module->version   = 2012090902; // The current module version (Date: YYYYMMDDXX)
$module->requires  = 2012062500; // Requires this Moodle version
$module->cron      = 0; // Period for cron to check this module (secs)
$module->component = 'mod_certificate';

$module->maturity  = MATURITY_STABLE;
$module->release   = "2.3 (2012090902)"; // User-friendly version number
