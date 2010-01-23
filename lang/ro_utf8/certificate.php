<?PHP // $Id: certificate.php,v 3.1.8 2007/06/13 

//General functions
$string['modulename'] = 'Certificat';
$string['modulenameplural'] = 'Certificate';
$string['certificatename'] = 'Nume Certificat';
$string['certificate:view'] = 'Vizualizare Certificat';
$string['certificate:manage'] = 'Certificat';
$string['certificate:printteacher'] = 'Imprimă nume profesor';
$string['certificate:student'] = 'Obţine certificat';

//Adding an instance
$string['intro'] = 'Introducere';
$string['addlinklabel'] = 'Adaugă o nouă activitate asociată';
$string['addlinktitle'] = 'Executaţi clic pentru a adăuga o nouă activitate asociată';
$string['issueoptions'] = 'Opţiuni emitere';
$string['textoptions'] = 'Opţiuni Text';
$string['designoptions'] = 'Opţiuni Design';
$string['lockingoptions'] = 'Opţiuni blocare';
$string['certificatetype'] = 'Tip Certificat';
$string['emailteachers'] = 'Trimite mesaj Email la profesori';
$string['emailothers'] = 'Trimite mesaj Email altor utilizatori în afară de profesori';
$string['savecertificate'] = 'Salvează Certificate';
$string['deliver'] = 'Livrare';
$string['download'] = 'Descărcare obligatorie';
$string['openbrowser'] = 'Deschide altă fereastră';
$string['emailcertificate'] = 'Trimite mesaj Email (salvaţi înainte de a trimite!)';
$string['border'] = 'Chenar';
$string['borderstyle'] = 'Chenar Imagine';
$string['borderlines'] = 'Linii';
$string['bordercolor'] = 'Linii Chenar';
$string['borderblack'] = 'Negru';
$string['borderbrown'] = 'Maro';
$string['borderblue'] = 'Albastru';
$string['bordergreen'] = 'Verde';
$string['printwmark'] = 'Transparenţă';
$string['datehelp'] = 'Dată';
$string['dateformat'] = 'Format Dată';
$string['userdateformat'] = 'Format dată în limba în care este afişată interfaţa';
$string['receiveddate'] = 'Dată primire';
$string['courseenddate'] = 'Dată final curs (obligatoriu!)';
$string['gradedate'] = 'Data obţinerii notei';
$string['printcode'] = 'Imprimă cod';
$string['printgrade'] = 'Imprimă notă';
$string['printoutcome'] = 'Imprimă rezultat';
$string['nogrades'] = 'Nu există note disponibile';
$string['gradeformat'] = 'Format notă';
$string['gradepercent'] = 'Notare cu procentaj';
$string['gradepoints'] = 'Notare cu puncte';
$string['gradeletter'] = 'Notare cu litere';
$string['printhours'] = 'Imprimă ore credit';
$string['printsignature'] = 'Imagine semnătură';
$string['sigline'] = 'linie';
$string['printteacher'] = 'Imprimă nume profesor(i)';
$string['customtext'] = 'Text personalizat';
$string['printdate'] = 'Imprimă dată';
$string['printseal'] = 'Ştampilă sau Logo';
$string['lockgrade'] = 'Blocare notă';
$string['requiredgrade'] = 'Notă minimă curs';
$string['coursetime'] = 'Timp minim curs';
$string['linkedactivity'] = 'Activitate asociată';
$string['minimumgrade'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Notă minimă';
$string['activitylocklabel'] = 'Activitate asociată/Nota minimă %';
$string['coursetimedependency'] = 'Număr minimum de minute accesare curs';
$string['activitydependencies'] = 'Activităţi obligatorii';

//Strings for verification block
$string['entercode'] = 'Completaţi codul certificatului pe care doriţi să-l verificaţi:';
$string['validate'] = 'Verifică';
$string['certificate'] = 'Verificarea codului certificatului:';
$string['verifycertificate'] = 'Verifică certificat';
$string['notfound'] = 'Codul certificatului nu a putut fi validat.';
$string['back'] = 'Inapoi';
$string['to'] = 'Atribuit';
$string['course'] = 'Cursul';
$string['date'] = 'Data obținerii';
$string['grade'] = 'Nota';

//Certificate view, index, report strings
$string['incompletemessage'] = 'Pentru a putea descărca certificatul va trebui să fi terminat toate activităţile obligatorii. Accesaţi cursul şi terminaţi temele.';
$string['awardedto'] = 'Atribuit';
$string['issued'] = 'Emis';
$string['notissued'] = 'Nu a fost emis';
$string['notissuedyet'] = 'Nu a fost încă emis';
$string['notreceived'] = 'Nu aţi obţinut acest certificat';
$string['getcertificate'] = 'Obţine certificat';
$string['report'] = 'Raport';
$string['code'] = 'Cod';
$string['viewed'] = 'Aţi obţinut acest certificat la data:';
$string['viewcertificateviews'] = 'Afişează $a certificate emise';
$string['reviewcertificate'] = 'Afişare certificat';
$string['openwindow'] = 'Executaţi clic pe butonul de mai jos pentru a deschide certificatul
într-o fereastră nouă.';
$string['opendownload'] = 'Executaţi clic pe butonul de mai jos pentru a salva certificatul
în computerul dvs.';
$string['openemail'] = 'Executaţi clic pe butonul de mai jos dacă doriţi ca certificatul
să vă fie trimis ca ataşament la un mesaj email.';
$string['receivedcerts'] = 'Certificate primite';
$string['errorlockgrade'] = 'Nota dvs. la $a->mod ($a->current%%) se situează sub nivelul notei minime ($a->needed%%) necesare pentru a putea primi acest certificat.';
$string['errorlocksurvey'] = 'Inainte de a putea primi certificatul va trebui să răspundeţi la toate chestionarele din cadrul cursului.';
$string['errorlockgradecourse'] = 'Nota dvs. la cursul ($a->current%%) se situează sub nivelul notei minime ($a->needed%%) necesare pentru a putea primi acest certificat.';
$string['errorlocktime'] = 'Pentru a putea primi acest certificat va trebui să îndepliniţi cerinţa privitoare la perioada minimă de timp petrecută studiind acest curs.';
$string['errorlockmod'] = 'Pentru a putea primi acest certificat va trebui să aveţi note de trecere la toate activităţile asociate acestui curs.';

//Email text
$string['emailstudenttext'] = 'Primiţi ataşat certificatul de absolvire a cursului $a->course.';
$string['awarded'] = 'Atribuit';
$string['emailteachermail'] = '
$a->student a primit certificatul \'$a->certificate\' 
de absolvire a cursului $a->course.

Pentru a vizualiza certificatul accesaţi:

    $a->url';
$string['emailteachermailhtml'] = '
$a->student a primit certificatul \'<i>$a->certificate</i>\'
de absolvire a cursului $a->course.

Pentru a vizualiza certificatul accesaţi:

    <a href=\"$a->url\">Certificate Report</a>.';

//Names of type folders
$string['typeportrait'] = 'Portrait';
$string['typeletter_portrait'] = 'Portrait (letter)';
$string['typelandscape'] = 'Landscape';
$string['typeletter_landscape'] = 'Landscape (letter)';
$string['typeunicode_landscape'] = 'Unicode (landscape)';
$string['typeunicode_portrait'] = 'Unicode (portrait)';

//Print to certificate strings
$string['grade'] = 'Notă';
$string['coursegrade'] = 'Notă curs';
$string['credithours'] = 'Ore credit';

$string['titlelandscape'] = 'CERTIFICAT de ABSOLVIRE';
$string['introlandscape'] = 'Se certifica prin prezentul ca';
$string['statementlandscape'] = 'a absolvit cursul';

$string['titleletterlandscape'] = 'CERTIFICAT de ABSOLVIRE';
$string['introletterlandscape'] = 'Se certifica prin prezentul ca';
$string['statementletterlandscape'] = 'a absolvit cursul';

$string['titleportrait'] = 'CERTIFICAT de ABSOLVIRE';
$string['introportrait'] = 'Se certifica prin prezentul ca';
$string['statementportrait'] = 'a absolvit cursul';
$string['ondayportrait'] = 'azi';

$string['titleletterportrait'] = 'CERTIFICAT de ABSOLVIRE';
$string['introletterportrait'] = 'Se certifica prin prezentul ca';
$string['statementletterportrait'] = 'a absolvit cursul';

//Certificate transcript strings
$string['notapplicable'] = 'N/A';
$string['certificatesfor'] = 'Certificate pentru';
$string['coursename'] = 'Curs';
$string['viewtranscript'] = 'Afişează certificate';
$string['mycertificates'] = 'Certificatele mele';
$string['nocertificatesreceived'] = 'nu a primit niciun certificat de absolvire.';
$string['notissued'] = 'Nu a fost primit';
$string['reportcertificate'] = 'Raportare Certificate';
$string['certificatereport'] = 'Raport certificate';
$string['printerfriendly'] = 'Pagină optimizată pentru imprimare';

?>