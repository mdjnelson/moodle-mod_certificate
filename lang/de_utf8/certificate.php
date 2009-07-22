<?PHP // $Id$ 
      // certificate.php - created with Moodle 1.6.1 (2006050510)
      //contributed by Ralf Hilgenstock

//General functions
$string['modulename'] = 'Zertifikat';
$string['modulenameplural'] = 'Zertifikate';
$string['certificatename'] = 'Certificate Name';
$string['certificate:view'] = 'View Certificate';
$string['certificate:manage'] = 'Manage Certificate';
$string['certificate:printteacher'] = 'Print Teacher';
$string['certificate:student'] = 'Get Certificate';

//Adding an instance
$string['addlinklabel'] = 'Add another linked activity option';
$string['addlinktitle'] = 'Click to add another linked activity option';
$string['issueoptions'] = 'Issue Options';
$string['textoptions'] = 'Text Options';
$string['designoptions'] = 'Design Options';
$string['lockingoptions'] = 'Locking Options';
$string['certificatetype'] = 'Zertifikatsformat';
$string['emailteachers'] = 'E-Mail an Trainer/innen';
$string['emailothers'] = 'E-Mail others';
$string['savecertificate'] = 'Zertifikate speichern';
$string['deliver'] = 'Anzeigeformat';
$string['download'] = 'Download';
$string['openbrowser'] = 'In einem neuen Fenster anzeigen';
$string['emailcertificate'] = 'E-Mail-Dateianhang';
$string['border'] = 'Rahmen';
$string['borderstyle'] = 'Border Image';
$string['borderlines'] = 'Linien';
$string['bordercolor'] = 'Border Linien';
$string['borderblack'] = 'Schwarz';
$string['borderblue'] = 'Blau';
$string['borderbrown'] = 'Brown';
$string['bordergreen'] = 'Grün';
$string['dateformat'] = 'Datumsformat';
$string['datehelp'] = 'Datum';
$string['printwmark'] = 'Wasserzeichen drucken';
$string['receiveddate'] = 'Ausstellungsdatum';
$string['courseenddate'] = 'Kursende (muß gesetzt werden)';
$string['printcode'] = 'Code drucken';
$string['printdate'] = 'Datum drucken';
$string['printgrade'] = 'Bewertung drucken';
$string['printseal'] = 'Stempel/Siegel drucken';
$string['printsignature'] = 'Unterschrift drucken';
$string['sigline'] = 'Linie';
$string['printteacher'] = 'Name  Trainer/in drucken';
$string['coursegradeoption'] = 'Kursbewertung';
$string['nogrades'] = 'Keine Bewertungen vorhanden';
$string['gradeformat'] = 'Format der Bewertung';
$string['gradeletter'] = 'Buchstaben-Note';
$string['gradepercent'] = 'Prozentwert als Note';
$string['gradepoints'] = 'Punkte als Note';
$string['printhours'] = 'Print Credit Hours';
$string['customtext'] = 'Custom Text';
$string['lockgrade'] = 'Lock by grade';
$string['requiredgrade'] = 'Required grade';
$string['coursetime'] = 'Required course time';
$string['linkedactivity'] = 'Linked Activity';
$string['minimumgrade'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Required Grade';
$string['activitylocklabel'] = 'Linked Activity/Minimum Grade %';
$string['coursetimedependency'] = 'Minimum required minutes in course';
$string['activitydependencies'] = 'Dependent activities';

//Strings for verification block 
$string['configcontent'] = 'Config content';
$string['validate'] = 'Prüfen';
$string['verifycertificate'] = 'Zertifikat abrufen';
$string['certificate'] = 'Verification for certificate code:';
$string['dontallowall'] = 'Do not allow all';
$string['cert'] = '#';
$string['notfound'] = 'The certificate number could not be validated.';
$string['back'] = 'Back';
$string['to'] = 'Awarded to';
$string['course'] = 'For';
$string['date'] = 'On';


//Certificate view, index, report strings
$string['incompletemessage'] = 'In order to download your certificate, you must first complete all required '.
                               'activities. Please return to the course to complete your coursework.';
$string['issued'] = 'ausgestellt';
$string['notissued'] = 'Nicht ausgestellt';
$string['notissuedyet'] = 'Bisher noch nicht ausgestellt';
$string['getcertificate'] = 'Hier erhalten Sie Ihr Zertifikat';
$string['code'] = 'Code';
$string['report'] = 'Bericht';
$string['viewed'] = 'Dieses Zertifikat wurde für Sie erstellt am:';
$string['viewcertificateviews'] = '$a erstellte Zertifikate anzeigen';
$string['reviewcertificate'] = 'Zertifikat aufrufen';
$string['openwindow'] = 'Klicken Sie den Button, um das Zertifikat in einem neuen Fenster zu öffnen.';
$string['opendownload'] = 'Klicken Sie den Button, um das Zertifikat auf dem PC zu speichern.';
$string['openemail'] = 'Klicken Sie den Button. Das Zertifikat wird Ihnen per E-Mail zugesandt.';
$string['receivedcerts'] = 'Erstellte Zertifikate';
$string['errorlockgrade'] = 'Your current grade on $a->mod ($a->current%%) is below the grade required ($a->needed%%) to receive the certificate.';
$string['errorlockgradecourse'] = 'Your current course grade ($a->current%%) is below the grade required ($a->needed%%) to receive your certificate.';
$string['errorlocktime'] = 'You must first meet the requirement for time spent working in this course before receving your certificate.';
$string['errorlockmod'] = 'You must first meet all course activity grade requirements before receving your certificate.';

//Email text
$string['awarded'] = 'Zertifikat erstellt';
$string['emailstudenttext'] = 'Im Anhang finden Sie Ihr Zertifikat zum Kurs \'$a->course\'.';
$string['emailteachermail'] = '$a->student hat das \'$a->certificate\' für den Kurs \'$a->course\' erhalten.';
$string['emailteachermailhtml'] = '$a->student hat das \'<i>$a->certificate</i>\' für den Kurs \'$a->course\' erhalten.

You can review it here:

    <a href=\"$a->url\">Certificate Report</a>.';

//Names of type folders
$string['typelandscape'] = 'Querformat (A4)';
$string['typeletter_landscape'] = 'Querformat (Letter)';
$string['typeletter_portrait'] = 'Hochformat (Letter)';
$string['typeportrait'] = 'Hochformat (A4)';
$string['typeunicode_landscape'] = 'Unicode (landscape)';
$string['typeunicode_portrait'] = 'Unicode (portrait)';

//Print to certificate strings
$string['grade'] = 'Bewertung';
$string['coursegrade'] = 'Kursbewertung';
$string['credithours'] = 'Credit Hours';

$string['title'] = 'Abschlußzertifikat';
$string['certify'] = 'This is to certify that';
$string['statement'] = 'has completed the course';

?>
