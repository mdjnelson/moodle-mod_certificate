<?PHP // $Id: version.php,v 2.0

///////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of certificate
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
///////////////////////////////////////////////////////////////////////////////

$module->version  = 2009062900;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2010080300;  // Requires this Moodle version
$module->cron     = 0;           // Period for cron to check this module (secs)

?>