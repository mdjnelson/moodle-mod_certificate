<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from view.php
}


/*
$certhtml = "<dl>\n";
$certhtml .= '<dt>name</dt><dd>' . fullname($USER) . "</dd>\n";
$certhtml .= '<dt>date</dt><dd>' . certificate_get_date($certificate, $certrecord, $course) . "</dd>\n";
$certhtml .= '<dt>course</dt><dd>' . $course->fullname . "</dd>\n";
$certhtml .= '<dt>div</dt><dd>' . substr($course->idnumber, 0, 2) . "</dd>\n";
$certhtml .= '<dt>hours</dt><dd>' .  $certificate->printhours . "</dd>\n";
$certhtml .= '<dt>thours</dt><dd>' .  $certificate->tcleosehours . "</dd>\n";
$certhtml .= '<dt>courseid</dt><dd>' . $course->id . "</dd>\n";
$certhtml .= "</dl><br />\n";

$certhtml .=  "<dl>\n";
$certhtml .= '<dt>certificate name</dt><dd>' . $certificate->name . "</dd>\n";
$certhtml .= '<dt>outcome</dt><dd>' . certificate_get_outcome($certificate, $course) . "</dd>\n";
$certhtml .= '<dt>code</dt><dd>' . certificate_get_code($certificate, $certrecord) . "</dd>\n";
$certhtml .= '<dt>customtext</dt><dd>' .  $certificate->customtext . "</dd>\n";
$certhtml .= '<dt>course id number</dt><dd>' .  $course->idnumber . "</dd>\n";
$certhtml .= "</dl>\n";
*/

 
$flashvars = array(
                  'name'     => fullname($USER),
                  'date'     => certificate_get_date($certificate, $certrecord, $course),
                  'course'   => $course->fullname,
                  'div'      => substr($course->idnumber, 0, 2),
                  'hours'    =>  $certificate->printhours,
                  'thours'   =>  $certificate->tcleosehours,
                  'courseid' =>  $course->id
                  );


// $flashvars_encoded = htmlspecialcharacters( http_build_query($flashvars) );
$flashvars_encoded = http_build_query($flashvars);

$certhtml = <<<HERE
               <div id="flashContent">
                        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="550" height="425" id="certificate2" align="middle">
                                <param name="FlashVars" value="$flashvars_encoded" />
                                <param name="movie" value="certificate2.swf" />
                                <param name="quality" value="high" />
                                <param name="bgcolor" value="#ffffff" />
                                <param name="play" value="true" />
                                <param name="loop" value="true" />
                                <param name="wmode" value="window" />
                                <param name="scale" value="showall" />
                                <param name="menu" value="true" />
                                <param name="devicefont" value="false" />
                                <param name="salign" value="" />
                                <param name="allowScriptAccess" value="sameDomain" />
                                <!--[if !IE]>-->
                                <object type="application/x-shockwave-flash" data="certificate2.swf" width="550" height="425">
                                        <param name="movie" value="certificate2.swf" />
                                        <param name="quality" value="high" />
                                        <param name="bgcolor" value="#ffffff" />
                                        <param name="play" value="true" />
                                        <param name="loop" value="true" />
                                        <param name="wmode" value="window" />
                                        <param name="scale" value="showall" />
                                        <param name="menu" value="true" />
                                        <param name="devicefont" value="false" />
                                        <param name="salign" value="" />
                                        <param name="allowScriptAccess" value="sameDomain" />
                                <!--<![endif]-->
                                        <a href="http://www.adobe.com/go/getflash">
                                                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
                                        </a>
                                <!--[if !IE]>-->
                                </object>
                                <!--<![endif]-->
                        </object>
                </div>
HERE;


// certificate_get_grade($certificate, $course);
/*
$i = 0;
if ($certificate->printteacher) {
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
        foreach ($teachers as $teacher) {
            $i++;
            certificate_print_text($pdf, $sigx, $sigy + ($i * 12), 'L', 'Times', '', 12, fullname($teacher));
        }
    }
}
*/

?>
