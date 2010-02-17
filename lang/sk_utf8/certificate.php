<?PHP // $Id: certificate.php,v 3.1.9 2008/01/20

//General functions
$string['modulename'] = 'Certifikát';
$string['modulenameplural'] = 'Certifikáty';
$string['certificatename'] = 'Názov Certifikátu';
$string['certificate:view'] = 'Zobraziť Certifikát';
$string['certificate:manage'] = 'Riadiť Certifikáty';
$string['certificate:printteacher'] = 'Zobraziť učiteľa';
$string['certificate:student'] = 'Vydať certifikát';

//Adding an instance
$string['intro'] = 'Oboznámenie';
$string['addlinklabel'] = 'Pridať ďalšiu aktivitu';
$string['addlinktitle'] = 'Kliknite a pridajte ďalšiu pripojenú aktivitu';
$string['issueoptions'] = 'Možnosti vydania';
$string['textoptions'] = 'Možnosti textu';
$string['designoptions'] = 'Možnosti vzhľadu';
$string['lockingoptions'] = 'Možnosti zamknutia';
$string['certificatetype'] = 'Typ certifikátu';
$string['emailteachers'] = 'Email učiteľovi';
$string['emailothers'] = 'Email ostatným';
$string['savecertificate'] = 'Uložiť certifikát';
$string['deliver'] = 'Doručenie';
$string['download'] = 'Stiahnuť';
$string['openbrowser'] = 'Otvoriť v novom okne';
$string['emailcertificate'] = 'Email (Musí sa tiež zvoliť možnosť "uložiť"!)';
$string['border'] = 'Okraj';
$string['borderstyle'] = 'Štýl okrajov';
$string['borderlines'] = 'Čiary';
$string['bordercolor'] = 'Farba okrajov';
$string['borderblack'] = 'Čierna';
$string['borderbrown'] = 'Hnedá';
$string['borderblue'] = 'Modrá';
$string['bordergreen'] = 'Zelená';
$string['printwmark'] = 'Pozadie';
$string['datehelp'] = 'Dátum';
$string['dateformat'] = 'Formát dátumu';
$string['userdateformat'] = 'Užívateľov formát dátumu';
$string['receiveddate'] = 'Deň prijatia';
$string['courseenddate'] = 'Dátum ukončenia kurzu (Musí byť určený!)';
$string['gradedate'] = 'Dátum známkovania';
$string['printcode'] = 'Zobraziť kód kurzu';
$string['printgrade'] = 'Zobraziť výslednú známku';
$string['printoutcome'] = 'Zobraziť výsledok';
$string['nogrades'] = 'Žiadna stupnica nie je dostupná';
$string['gradeformat'] = 'Formát známkovania';
$string['gradepercent'] = 'Známkovanie v percentách';
$string['gradepoints'] = 'Bodové známkovanie';
$string['gradeletter'] = 'Písmové známkovanie';
$string['printhours'] = 'Zobraziť strávený čas ';
$string['printsignature'] = 'Obrázok podpisu';
$string['sigline'] = 'Čiara';
$string['printteacher'] = 'Vytlačiť meno učiteľa';
$string['customtext'] = 'Ľubovolný text';
$string['printdate'] = 'Vytlačiť dátum';
$string['printseal'] = 'Obrázok pečate alebo loga';
$string['lockgrade'] = 'Uzamknuté podľa stupňa známkovania';
$string['requiredgrade'] = 'Požadovaný počet bodov (%)';
$string['coursetime'] = 'Požadovaný strávený čas na kurz';
$string['linkedactivity'] = 'Spojené aktivity';
$string['minimumgrade'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Požadovaný stupeň';
$string['activitylocklabel'] = 'Spojené aktivity/minimálny počet bodov (%)';
$string['coursetimedependency'] = 'Minimálny požadovaný počet minút v kurze';
$string['activitydependencies'] = 'Podmienené aktivity';

//Strings for verification block
$string['entercode'] = 'Zadajte kód certifikátu, ktorý chcete overiť:';
$string['validate'] = 'Overiť';
$string['certificate'] = 'Overenie certifikačného kódu:';
$string['verifycertificate'] = 'Overiť certifikát';
$string['notfound'] = 'Číslo certifikátu nie je platné.';
$string['back'] = 'Naspäť';
$string['to'] = 'Udelený';
$string['course'] = 'Pre';
$string['date'] = 'Dňa';
$string['grade'] = 'Stupeň';

//Certificate view, index, report strings
$string['incompletemessage'] = 'Ak chcete stiahnuť certifikát, musíte najskôr ukončiť všetky požadované aktivity. '.'Prosím vráťte sa a ukončite kurz.';
$string['awardedto'] = 'Udelené';
$string['issued'] = 'Vydané';
$string['notissued'] = 'Nevydané';
$string['notissuedyet'] = 'Nevydané';
$string['notreceived'] = 'Ešte ste nedostali tento certifikát';
$string['getcertificate'] = 'Dostať certifikát';
$string['report'] = 'Správa';
$string['code'] = 'Kód';
$string['viewed'] = 'Dostali ste tento certifikát dňa:';
$string['viewcertificateviews'] = 'Zobraziť $a vydané certifikáty';
$string['reviewcertificate'] = 'Zobraziť vaše certifikáty';
$string['openwindow'] = 'Ak chcete zobraziť vaše certifikáty v novom okne, kliknite na tlačidlo.';
$string['opendownload'] = 'Ak chcete uložiť certifikáty vo vašom počítači, kliknite na tlačidlo.';
$string['openemail'] = 'Ak si chcete nechať certifikát zaslať emailom ako prílohu, kliknite na tlačidlo.';
$string['receivedcerts'] = 'Prijaté certifikáty';
$string['errorlockgrade'] = 'Váš súčasný stupeň $a->mod ($a->current%%) je nižší ako požadovaný ($a->needed%%) na vydanie certifikátu.';
$string['errorlocksurvey'] = 'Aby ste dostali certifikát, musíte najskôr ukončiť zhodnotenie.';
$string['errorlockgradecourse'] = 'Vaše súčasné hodnotenie ($a->current%%) je menej ako požadované ($a->needed%%) na to, aby ste dostali certifikát.';
$string['errorlocktime'] = 'Na vydanie certifikátu musíte spĺňať časové kritérium, ktoré je nastavené na potrebný čas strávený v kurze.';
$string['errorlockmod'] = 'Na získanie certifikátu je potrebné splniť všetky aktivity kurzu.';

//Email text
$string['emailstudenttext'] = 'V prílohe je váš certifikát pre kurz $a->course.';
$string['awarded'] = 'Ocenený';
$string['emailteachermail'] = '
$a->student získal tento certifikát: \'$a->certificate\'
za $a->course.

Môžete si ho prezrieť tu:

    $a->url';
$string['emailteachermailhtml'] = '
$a->Študent získal tento certifikát: \'<i>$a->certificate</i>\'
v kurze $a->course.

Môžete si ho prezrieť tu:

    <a href=\"$a->url\">Certifikát</a>.';

//Names of type folders
$string['typeportrait'] = 'Vertikálne';
$string['typeletter_portrait'] = 'Vertikálne (list)';
$string['typelandscape'] = 'Horizontálne';
$string['typeletter_landscape'] = 'Horizontálne (list)';
$string['typeunicode_landscape'] = 'Unicode (Horizontálne)';
$string['typeunicode_portrait'] = 'Unicode (Vertikálne)';

//Print to certificate strings
$string['grade'] = 'Hodnotenie';
$string['coursegrade'] = 'Hodnotenie kurzu';
$string['credithours'] = 'Hodiny';

$string['titlelandscape'] = 'CERTIFIKÁT O ÚSPEŠNOM UKONČENÍ';
$string['introlandscape'] = 'Týmto certifikujeme';
$string['statementlandscape'] = 'za ukončenie kurzu';

$string['titleletterlandscape'] = 'CERTIFIKÁT O ÚSPEŠNOM UKONČENÍ';
$string['introletterlandscape'] = 'Týmto certifikujeme';
$string['statementletterlandscape'] = 'za ukončenie kurzu';

$string['titleportrait'] = 'CERTIFIKÁT O ÚSPEŠNOM UKONČENÍ';
$string['introportrait'] = 'Týmto certifikujeme';
$string['statementportrait'] = 'za ukončenie kurzu';
$string['ondayportrait'] = 'dňa';

$string['titleletterportrait'] = 'CERTIFIKÁT O ÚSPEŠNOM UKONČENÍ';
$string['introletterportrait'] = 'Týmto certifikujeme';
$string['statementletterportrait'] = 'za ukončenie kurzu';

//Certificate transcript strings
$string['notapplicable'] = 'N/A';
$string['certificatesfor'] = 'Certifikát pre';
$string['coursename'] = 'Kurz';
$string['viewtranscript'] = 'Zobraziť certifikáty';
$string['mycertificates'] = 'Moje certifikáty';
$string['nocertificatesreceived'] = 'Nedostal žiadny certifikát za kurz.';
$string['notissued'] = 'Neprijaté';
$string['reportcertificate'] = 'Oznámiť certifikát';
$string['certificatereport'] = 'Správa o certifikátoch';
$string['printerfriendly'] = 'Zobraziť na stránke pre tlač';
?>
