<?PHP

// Traslation by Leonardo Terra - 22/12/2006
//General functions

$string['modulename'] = 'Certificado';
$string['modulenameplural'] = 'Certificados';
$string['certificatename'] = 'Certificate Name';
$string['certificate:view'] = 'Ver certificado';
$string['certificate:manage'] = 'Configurar certificado';
$string['certificate:printteacher'] = 'Imprimir professor';
$string['certificate:student'] = 'Obter certificado';

//Adding an instance
$string['addlinklabel'] = 'Add another linked activity option';
$string['addlinktitle'] = 'Click to add another linked activity option';
$string['issueoptions'] = 'Issue Options';
$string['textoptions'] = 'Text Options';
$string['designoptions'] = 'Design Options';
$string['lockingoptions'] = 'Locking Options';
$string['certificatetype'] = 'Tipo de certificado';
$string['emailteachers'] = 'Enviar e-mail aos professores';
$string['emailothers'] = 'Email Others';
$string['savecertificate'] = 'Salvar certificados';
$string['deliver'] = 'Envio';
$string['download'] = 'Forçar download';
$string['openbrowser'] = 'Abrir em uma nova janela';
$string['emailcertificate'] = 'Enviar por E-mail (requer que salvar esteja ativado!)';
$string['border'] = 'Moldura';
$string['borderstyle'] = 'Border Image';
$string['borderlines'] = 'Linhas';
$string['bordercolor'] = 'Border Linhas';
$string['borderblack'] = 'Preto';
$string['borderbrown'] = 'Marrom';
$string['borderblue'] = 'Azul';
$string['bordergreen'] = 'Verde';
$string['printwmark'] = 'Imprimir marca d água';
$string['datehelp'] = 'Data';
$string['dateformat'] = 'Formato da Data';
$string['receiveddate'] = "Data de emissão";
$string['courseenddate'] = 'Data do Final do Curso (Precisa estar ajustado!)';
$string['printcode'] = 'Imprimir código do certificado';
$string['printgrade'] = 'Imprimir nota';
$string['coursegradeoption'] = 'Nota no curso';
$string['nogrades'] = 'Notas não disponíveis';
$string['gradeformat'] = 'Formato da nota';
$string['gradepercent'] = 'Porcentagem';
$string['gradepoints'] = 'Pontos';
$string['gradeletter'] = 'Conceito';
$string['printhours'] = 'Imprimir carga horária';
$string['printsignature'] = 'Imprimir assinatura';
$string['sigline'] = 'Linha';
$string['printteacher'] = 'Imprimir professor';
$string['customtext'] = 'Custom Text';
$string['printdate'] = 'Imprimir data';
$string['printseal'] = 'Imprimir selo';
$string['lockgrade'] = 'Lock by grade';
$string['requiredgrade'] = 'Required grade';
$string['coursetime'] = 'Required course time';
$string['linkedactivity'] = 'Linked Activity';
$string['minimumgrade'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Required Grade';
$string['activitylocklabel'] = 'Linked Activity/Minimum Grade %';
$string['coursetimedependency'] = 'Minimum required minutes in course';
$string['activitydependencies'] = 'Dependent activities';

//strings for verification 
$string['configcontent'] = 'Configurar conteúdo';
$string['validate'] = 'Validar';
$string['certificate'] = 'Verificação para código de certificado:';
$string['verifycertificate'] = 'Validar o certificado';
$string['dontallowall'] = 'Não permitir a todos';
$string['cert'] = '#';
$string['notfound'] = 'O número do certificado não pôde ser validado.';
$string['back'] = 'Voltar';
$string['to'] = 'Concedido a';
$string['course'] = 'Para';
$string['date'] = 'Emitido em';

//Certificate view, index, report strings
$string['incompletemessage'] = 'In order to download your certificate, you must first complete all required '.
                               'activities. Please return to the course to complete your coursework.';
$string['issued'] = 'Emitido';
$string['notissued'] = 'Não emitido';
$string['notissuedyet'] = 'Ainda não emitido';
$string['notreceived'] = 'Certificado inexistente ou não emitido';
$string['getcertificate'] = 'Obtenha seu certificado';
$string['report'] = 'Relatório';
$string['code'] = 'Código';
$string['viewed'] = 'Você recebeu este certificado em:';
$string['viewcertificateviews'] = 'Relatório de certificados emitidos';
$string['reviewcertificate'] = 'Ver o certificado';
$string['openwindow'] = 'Clique no botão abaixo para abrir seu certificado em uma nova janela.';
$string['opendownload'] = 'Clique no botão abaixo para salvar seu certificado em seu computador.';
$string['openemail'] = 'Clique no botão abaixo para seu certificado ser encaminhado por e-mail.';
$string['receivedcerts'] = 'Certificados recebidos';
$string['errorlockgrade'] = 'Your current grade on $a->mod ($a->current%%) is below the grade required ($a->needed%%) to receive the certificate.';
$string['errorlocksurvey'] = 'You need to complete all course surveys before receving your certificate.';
$string['errorlockgradecourse'] = 'Your current course grade ($a->current%%) is below the grade required ($a->needed%%) to receive your certificate.';
$string['errorlocktime'] = 'You must first meet the requirement for time spent working in this course before receving your certificate.';
$string['errorlockmod'] = 'You must first meet all course activity grade requirements before receving your certificate.';

//Email text
$string['emailstudenttext'] = 'Encontra-se anexado a este e-mail o seu certificado para o curso de $a->course.';
$string['awarded'] = 'Concedido';
$string['emailteachermail'] = '
$a->Recebeu seu certificado: \'$a->certificate\'
para o curso de $a->course.

Você pode revisar este certificado em:

    $a->url';
$string['emailteachermailhtml'] = '
$a->Recebeu seu certificado: \'<i>$a->certificate</i>\'
para o curso de $a->course.

Você pode revisar este certificado em:

    <a href=\"$a->url\">Relatório de certificados</a>.';

//Names of type folders
$string['typeportrait'] = 'Retrato';
$string['typeletter_portrait'] = 'Retrato (Carta)';
$string['typelandscape'] = 'Paisagem';
$string['typeletter_landscape'] = 'Paisagem (Carta)';
$string['typeunicode_landscape'] = 'Unicode (Paisagem)';
$string['typeunicode_portrait'] = 'Unicode (portrait)';

//Print to certificate strings
$string['grade'] = 'Nota';
$string['coursegrade'] = 'Nota no curso';
$string['credithours'] = 'Carga horária';

$string['title'] = 'CERTIFICADO DE CONCLUSÃO';
$string['certify'] = 'Certifico para os devidos fins que';
$string['statement'] = 'completou o curso de';

?>
