<?PHP // $Id: version.php,v 3.1.0 latest update 2006/09/19 

///////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of certificate
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
///////////////////////////////////////////////////////////////////////////////

$module->version  = 2006081700;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2006050506;  // Requires this Moodle version
$module->cron     = 0;           // Period for cron to check this module (secs)

?>