<?PHP // $Id: certificate.php,v 3.1.9 2008/01/20

//General functions
$string['modulename'] = 'Certificaat';
$string['modulenameplural'] = 'Certificaten';
$string['certificatename'] = 'Naam certificaat';
$string['certificate:view'] = 'Bekijk certificaat';
$string['certificate:manage'] = 'Beheer certificaat';
$string['certificate:printteacher'] = 'Print docent';
$string['certificate:student'] = 'Certificaat ophalen';

//Adding an instance
$string['intro'] = 'Introductie';
$string['addlinklabel'] = 'Nog een gelinkte activiteit toevoegen';
$string['addlinktitle'] = 'Klik om nog een gelinkte activiteit toe te voegen';
$string['issueoptions'] = 'Uitlevering opties';
$string['textoptions'] = 'Certificaat opties';
$string['designoptions'] = 'Layout opties';
$string['lockingoptions'] = 'Beveiligingsopties';
$string['certificatetype'] = 'Certificaat type';
$string['emailteachers'] = 'Email docenten';
$string['emailothers'] = 'Email anderen';
$string['savecertificate'] = 'Certificaten opslaan';
$string['deliver'] = 'Uitlevering';
$string['download'] = 'Forceer download';
$string['openbrowser'] = 'Open in nieuw venster';
$string['emailcertificate'] = 'Email (U moet ook nog opslaan!)';
$string['border'] = 'Rand';
$string['borderstyle'] = 'Rand afbeelding';
$string['borderlines'] = 'Lijnen';
$string['bordercolor'] = 'Rand Lijnen';
$string['borderblack'] = 'Zwart';
$string['borderbrown'] = 'Bruin';
$string['borderblue'] = 'Blauw';
$string['bordergreen'] = 'Groen';
$string['printwmark'] = 'Watermerk afbeelding';
$string['datehelp'] = 'Datum';
$string['dateformat'] = 'Datum Formaat';
$string['userdateformat'] = 'Datum formaat in de taal van de cursist';
$string['receiveddate'] = 'Datum ontvangen';
$string['courseenddate'] = 'Einddatum cursus (moet ingesteld worden!)';
$string['gradedate'] = 'Geslaag datum';
$string['printcode'] = 'Print code';
$string['printgrade'] = 'Print graad';
$string['printoutcome'] = 'Print uitkomst';
$string['nogrades'] = 'Geen graden beschikbaar';
$string['gradeformat'] = 'Scoring methode';
$string['gradepercent'] = 'Percentage';
$string['gradepoints'] = 'Punten';
$string['gradeletter'] = 'Letters';
$string['printhours'] = 'Print leerminuten';
$string['printsignature'] = 'Handtekening afbeelding';
$string['sigline'] = 'lijn';
$string['printteacher'] = 'Print docent naam(en)';
$string['customtext'] = 'Aangepaste tekst';
$string['printdate'] = 'Print datum';
$string['printseal'] = 'Zegel of logo afbeelding';
$string['lockgrade'] = 'Beveilig bij score';
$string['requiredgrade'] = 'Minimale cursus score';
$string['coursetime'] = 'Minimale cursus tijd';
$string['linkedactivity'] = 'Gelinkte activiteit';
$string['minimumgrade'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Minimale score';
$string['activitylocklabel'] = 'Gelinkte activiteit/Minimale score %';
$string['coursetimedependency'] = 'Minimum aantal minuten in cursus';
$string['activitydependencies'] = 'Afhankelijke activiteiten';

//Strings for verification block
$string['entercode'] = 'Certificaat code:';
$string['validate'] = 'Controleer';
$string['certificate'] = 'Controle voor certificaat code:';
$string['verifycertificate'] = 'Controleer certificaat';
$string['notfound'] = 'Het certificaat nummer kan niet gevalideerd worden.';
$string['back'] = 'Terug';
$string['to'] = 'Toegekend aan';
$string['course'] = 'Voor';
$string['date'] = 'Op';
$string['grade'] = 'Score';

//Certificate view, index, report strings
$string['incompletemessage'] = 'Om uw certificaat te downloaden moet u eerst alle verplichte activiteiten afronden. Ga svp terug naar de cursus om ze af te ronden.';
$string['awardedto'] = 'Toegekend aan';
$string['issued'] = 'Uitgegeven';
$string['notissued'] = 'Niet uitgegeven';
$string['notissuedyet'] = 'Nog niet uitgegeven';
$string['notreceived'] = 'U heeft dit certificaat niet ontvangen';
$string['getcertificate'] = 'Haal uw certificaat op';
$string['report'] = 'Rapport';
$string['code'] = 'Code';
$string['viewed'] = 'U heeft dit certificaat ontvangen op:';
$string['viewcertificateviews'] = 'Bekijk $a uitgegeven certificaten';
$string['reviewcertificate'] = 'Bekijk uw certificaat';
$string['openwindow'] = 'Klik op de knop hieronder om uw certificaat in een nieuw venster te openen.';
$string['opendownload'] = 'Klik op de knop hieronder om uw certificaat te downloaden.';
$string['openemail'] = 'Klik op de knop hieronder om uw certificaat per e-mail te ontvangen.';
$string['receivedcerts'] = 'Ontvangen certificaten';
$string['errorlockgrade'] = 'Uw behaalde score op $a->mod ($a->current%%) is lager dan wat nodig is ($a->needed%%) om het certificaat te verkrijgen.';
$string['errorlocksurvey'] = 'U dient alle cursus enquetes in te vullen voordat u het certificaat kunt verkrijgen.';
$string['errorlockgradecourse'] = 'Uw behaalde score ($a->current%%) is lager dan wat nodig is ($a->needed%%) om het certificaat te verkrijgen.';
$string['errorlocktime'] = 'U heeft nog niet lang genoeg in de cursus gewerkt om het certificaat te verkrijgen.';
$string['errorlockmod'] = 'U moet eerst het examen succesvol afronden voordat u het certificaat kunt ophalen.';

//Email text
$string['emailstudenttext'] = 'Bijgevoegd is uw certificaat voor $a->course.';
$string['awarded'] = 'Toegekend';
$string['emailteachermail'] = '
$a->student heeft het certificaat: \'$a->certificate\'
behaald voor $a->course.

U kunt het certificaat hier bekijken:

    $a->url';
$string['emailteachermailhtml'] = '
$a->student heeft het certificaat: \'<i>$a->certificate</i>\'
behaald voor  $a->course.

U kunt het certificaat hier bekijken:

    <a href=\"$a->url\">Certificaat rapport</a>.';

//Names of type folders
$string['typeportrait'] = 'Portrait';
$string['typeletter_portrait'] = 'Portrait (letter)';
$string['typelandscape'] = 'Landscape';
$string['typeletter_landscape'] = 'Landscape (letter)';
$string['typeunicode_landscape'] = 'Unicode (landscape)';
$string['typeunicode_portrait'] = 'Unicode (portrait)';

//Print to certificate strings
$string['grade'] = 'Score';
$string['coursegrade'] = 'Score';
$string['credithours'] = 'Aantal lesuren';

$string['titlelandscape'] = 'CERTIFICAAT';
$string['introlandscape'] = 'Dit certificaat bewijst dat';
$string['statementlandscape'] = 'het examen succesvol heeft afgerond:';

$string['titleletterlandscape'] = 'CERTIFICAAT';
$string['introletterlandscape'] = 'Dit certificaat bewijst dat';
$string['statementletterlandscape'] = 'het examen succesvol heeft afgerond:';

$string['titleportrait'] = 'CERTIFICAAT';
$string['introportrait'] = 'Dit certificaat bewijst dat';
$string['statementportrait'] = 'het examen succesvol heeft afgerond:';
$string['ondayportrait'] = 'datum';

$string['titleletterportrait'] = 'CERTIFICAAT';
$string['introletterportrait'] = 'Dit certificaat bewijst dat';
$string['statementletterportrait'] = 'het examen succesvol heeft afgerond:';

//Certificate transcript strings
$string['notapplicable'] = 'nvt';
$string['certificatesfor'] = 'Certificaten voor';	
$string['coursename'] = 'Cursus';
$string['viewtranscript'] = 'Bekijk certificaten';
$string['mycertificates'] = 'Mijn Certificaten';
$string['nocertificatesreceived'] = 'heeft geen enkel certificaat ontvangen.';
$string['notissued'] = 'Niet ontvangen';
$string['reportcertificate'] = 'Certificaten rapport';
$string['certificatereport'] = 'Certificaten rapport';
$string['printerfriendly'] = 'Print-vriendelijke pagina';
?>