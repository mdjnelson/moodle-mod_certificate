<?php

/**
 * Code fragment to define the version of the certificate module
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or late
 */

$module->version   = 2012092101; // The current module version (Date: YYYYMMDDXX)
$module->requires  = 2010112400; // Requires this Moodle version
$module->cron      = 0; // Period for cron to check this module (secs)
$module->component = 'mod_certificate';

$module->maturity  = MATURITY_STABLE;
$module->release   = "Stable (2012092101)"; // User-friendly version number
