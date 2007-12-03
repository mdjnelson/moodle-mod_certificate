<?PHP // $Id: version.php,v 3.1.8 

///////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of certificate
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
///////////////////////////////////////////////////////////////////////////////

$module->version  = 2007102803;  // The current module version (Date: YYYYMMDDXX)
$module->requires = 2007101504;  // Requires this Moodle version
$module->cron     = 0;           // Period for cron to check this module (secs)

?>