<?php

/**
 * Language strings for the certificate module
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <mark@moodle.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addlinklabel'] = 'Aggiungi un\'altra opzione';
$string['addlinktitle'] = 'Clicca qui per aggiungere un\'altra opzione';
$string['areaintro'] = 'Introduzione certificato';
$string['awarded'] = 'Conferito';
$string['awardedto'] = 'Conferito a';
$string['back'] = 'Indietro';
$string['border'] = 'Bordo';
$string['borderblack'] = 'Nero';
$string['borderblue'] = 'Blu';
$string['borderbrown'] = 'Marrone';
$string['bordercolor'] = 'Linee bordo';
$string['bordercolor_help'] = 'Siccome le immagini possono aumentare il peso del file finale è possibile scegliere di stampare delle semplici linee (assicurarsi che Bordo Immagine sia impostato su No). Il risultato finale sarà un bordo composto di tre linee di spessore variabile del colore scelto.';
$string['bordergreen'] = 'Verde';
$string['borderlines'] = 'Linee';
$string['borderstyle'] = 'Bordo Immagine';
$string['borderstyle_help'] = 'L\'opzione  Bordo Immagine permette di scegliere una immagine di sfondo dalla cartella certificate/pix/borders. Oltre alla bordo come immagine è possibile scegliere delle semplici linee o nessun bordo.';
$string['certificate:view'] = 'Vedi Certificato';
$string['certificate:manage'] = 'Gestisci Certificato';
$string['certificate:printteacher'] = 'Stampa Nome Docente';
$string['certificate:student'] = 'Preleva Certificato';
$string['certificate'] = 'Verifica codice certificato:';
$string['certificatename'] = 'Nome Certificato';
$string['certificatereport'] = 'Rapporto Certificato';
$string['certificatesfor'] = 'Certificato per';
$string['certificatetype'] = 'Tipo di Certificato';
$string['certificatetype_help'] = 'Questa impostazione controlla il tipo del Certificato. Il tipo di Certificato include:
A4 Incapsulato compone in formato A4 con font incapsulato nel documento
A4 Non-Incapsulato compone in formato A4 senza font incapsulati
Lettera Incapsulata compone in formato lettera con font incapsulato nel documento
Lettera Non-Incapsulata compone in formato lettera senza font incapsulati

I tipi non incapsulati usano font Helvetica e Times. Nel caso che gli utenti finali non avessero la disponiblità di questi font è possibile scegliere i tipi incapsulati.
I tipi incapsulati usano i font Dejavusans e Dejavusanserif. Questo appesantirà i documenti finali. I tipi non incapsulati sono la scelta raccomandata. 

Nuovi tipi possono essere aggiunti sotto la cartella certificate/type. Ricordarsi di aggiungere il nome del nuovo tipo nei file lingua certificato sotto la cartella lang.';

$string['certify'] = 'Questo documento certifica che';
$string['code'] = 'Codice';
$string['completiondate'] = 'Completamento Corso';
$string['course'] = 'Per';
$string['coursegrade'] = 'Valutazione Corso';
$string['coursename'] = 'Corso';
$string['credithours'] = 'Ore credito';
$string['customtext'] = 'Testo Personalizzato';
$string['customtext_help'] = 'Nel caso si voglia stampare un nome docente differente di quello assegnato nel corso come ruolo di docente non selezionare Stampa Docente e Immagine Firma. Specificare il nome docente nel Testo Personalizzato. Le seguenti entità html sono disponibili: &lt;br&gt;, &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;img&gt; (src e width (oppure height) sono obbligatori), &lt;a&gt; (href è obbligatorio), &lt;font&gt; (possibili attributi sono: color, (hex color code), face, (arial, times, courier, helvetica, symbol)).';
$string['date'] = 'Giorno ';
$string['datefmt'] = 'Formato Data';
$string['datefmt_help'] = 'Selezionare il formato data da stampare sul certificato. In alternativa selezionare l\'ultima opzione per avere la data stampata a dipendenza della lingua dell\'utente.';
$string['datehelp'] = 'Data';
$string['deletissuedcertificates'] = 'Rimuovi certificati rilasciati';
$string['delivery'] = 'Consegna';
$string['delivery_help'] = 'Selezionare qui le modalità di consegna del certificato
Apri nel Browser: Apre il certificato in una nuova finestra del Browser
Forza Salvataggio: Apre la finestra Salvataggio documento nel Browser
Spedisci per posta elettronica: Spedisce il certificato come allegato per posta elettronica. L\'accesso al certificato cliccando sulla risorsa Certificato nel corso è ancora possibile.';
$string['designoptions'] = 'Opzioni di Design';
$string['download'] = 'Forza Salvataggio';
$string['emailcertificate'] = 'Spedisci per posta elettronica (Bisogna selezionare salva!)';
$string['emailothers'] = 'Notifica altri';
$string['emailothers_help'] = 'Immettere qui gli indirizzi di posta elettronica separati da virgole di tutti quelli che dovrebbero ricevere la notifica di rilascio dei certificati.';
$string['emailstudenttext'] = 'In allegato il certificato per {$a->course}.';
$string['emailteachers'] = 'Notifica Docenti';
$string['emailteachers_help'] = 'Se abilitato tutti i docenti riceveranno la notifica del rilascio dei certificati.';
$string['emailteachermail'] = '
{$a->student} ha ricevuto il certificato : \'{$a->certificate}\'
per il corso {$a->course}.

Per verificare consultare il seguente indirizzo:

    {$a->url}';
$string['emailteachermailhtml'] = '
{$a->student} ha ricevuto il certificato: \'<i>{$a->certificate}</i>\'
per il corso {$a->course}.

Per verificare consultare il:

    <a href=\"{$a->url}\"> Rapporto Certificato</a>.';
$string['entercode'] = 'Inserire il codice di verifica:';
$string['getcertificate'] = 'Scarica Certificato';
$string['grade'] = 'Valutazione';
$string['gradedate'] = 'Data Valutazione';
$string['gradefmt'] = 'Formato Valutazione';
$string['gradefmt_help'] = 'Ci sono tre formati per la Valutazione sul Certificato:

Valutazione Percentuale: Stampa la valutazione in formato percentuale.
Valutazione Punti: Stampa i punti ottenuti. 
Valutazione Letterale: Stampa la valutazione in formato lettera.';
$string['gradeletter'] = 'Valutazione Letterale';
$string['gradepercent'] = 'Valutazione Percentuale';
$string['gradepoints'] = 'Valutazione Punti';
$string['incompletemessage'] = 'Per scaricare il certificato dovete prima completare tutte le '.'attività richieste.';
$string['intro'] = 'Introduzione';
$string['issueoptions'] = 'Opzioni di rilascio';
$string['issued'] = 'Rilasciato';
$string['issueddate'] = 'Data rilascio';
$string['landscape'] = 'Orizzontale';
$string['lastviewed'] = 'L\'ultimo rilascio del certificato risale a:';
$string['letter'] = 'Lettera';
$string['lockingoptions'] = 'Opzioni di bloccaggio';
$string['modulename'] = 'Certificato';
$string['modulenameplural'] = 'Certificati';
$string['mycertificates'] = 'I miei certificati';
$string['nocertificates'] = 'Non ci sono certificati.';
$string['nocertificatesissued'] = 'Non ci sono certificati rilasciati.';
$string['nocertificatesreceived'] = ' non ha ricevuto nessun certificato.';
$string['nogrades'] = 'Nessuna valutazione disponibile.';
$string['notapplicable'] = 'N/A';
$string['notfound'] = 'Il numero di certificato non può essere validato.';
$string['notissued'] = 'Non rilasciato';
$string['notissuedyet'] = 'Ancora non rilasciato';
$string['notreceived'] = 'Non hai ricevuto questo certificato';
$string['openbrowser'] = 'Apri in una nuova finestra';
$string['opendownload'] = 'Clicca il bottone sottostante per salvare il certificato sul tuo computer.';
$string['openemail'] = 'Clicca il bottone sottostante per la spedizione del certificato per posta elettronica.';
$string['openwindow'] = 'Clicca il bottone sottostante per aprire il certificato in una nuova finestra.';
$string['or'] = 'Oppure';
$string['orientation'] = 'Orientamento';
$string['orientation_help'] = 'Scegliere l\'orientamento verticale/orizzontale.';
$string['pluginadministration'] = 'Amministrazione Certificato';
$string['pluginname'] = 'Certificato';
$string['portrait'] = 'Verticale';
$string['printdate'] = 'Stampa data';
$string['printdate_help'] = 'Questa opzione abilita la stampa della data sul certificato. Se viene selezionata la data di completamento del corso e il certificato viene scaricato prima  del completamento la data non verrà stampata. È possibile scegliere di stampare la data della valutazione di una attività.';
$string['printerfriendly'] = 'Pagina per la stampa';
$string['printhours'] = 'Stampa ore credito';
$string['printhours_help'] = 'Immettere qui le ore credito da stampare sul certificato.';
$string['printgrade'] = 'Stampa Valutazione';
$string['printgrade_help'] = 'È possibile scegliere diverse voci disponibili nella tabella valutazioni da stampare nel certificato. Le valutazioni sono stampate secondo l\'ordine nel quale appaiono nella tabella valutazioni. Scegliere sotto il formato della valutazione.';
$string['printnumber'] = 'Stampa Codice';
$string['printnumber_help'] = 'Un codice univoco di 10 cifre a lettere può essere aggiunto al certificato. Questo numero può essere usato come verifica confrontandolo con i codici nel rapporto sui certificati.';
$string['printoutcome'] = 'Stampa Risultato';
$string['printoutcome_help'] = 'È possibile stampare il risultato ottenuto dall\'utente. Nel certificato è possibile stampare i risultati delle singole attività. Un esempio potrebbe essere Risultato Compito: Sufficiente';
$string['printseal'] = 'Sigillo oppure Logo';
$string['printseal_help'] = 'Questa opzione permette di selezionare il sigillo oppure un logo da stampare sul certificato. La posizione predefinita dell\'immagine è in basso a destra.';
$string['printsignature'] = 'Immagine della firma';
$string['printsignature_help'] = 'Questa opzione permette di stampare l\'immagine della firma (contenute sotto certificate/pix/signatures). In alternativa alla immagine è possibile stampare una linea per la firma scritta. La posizione predefinita della immagine è in basso a sinistra.';
$string['printteacher'] = 'Stampa nome/i Docenti';
$string['printteacher_help'] = 'Nel caso si abbia più certificati è possibile assegnare il ruolo di docente al livello del modulo(certificato) stesso. Questo è utile se ci sono più docenti nel corso e si vuole stampare nomi diversi per ogni certificato. Per aggiungere docenti al livello del certificato cliccare sul icona Modifica il certificato a poi cliccare sul tab dei ruoli locali. Assegnare quindi il ruolo di Teacher (editing teacher) nel certificato. L\'utente scelto può avere un ruolo differente al livello del corso. Tutti i nomi aggiunti verranno quindi stampati nel certificato.';
$string['printwmark'] = 'Immagine Filigrana';
$string['printwmark_help'] = 'Una immagine Filigrana può essere impostata come sfondo del certificato. La filigrana avrà un effetto sbiadito. La filigrana può essere un logo, un sigillo, oppure una immagine qualsiasi.';
$string['receivedcerts'] = 'Certificati Ricevuti';
$string['receiveddate'] = 'Ricevuto il giorno';
$string['reissuecert'] = 'Rilascia il Certificato ancora';
$string['reissuecert_help'] = 'Se scegliete Si qui il certificato verrà rilasciato con una nuova data e valutazione ogni volta che viene scaricato. Nota: Anche se la tabella mostrerà il rilasci passati nessun bottone Rivedi sarà disponibile. Solo l\'ultimo certificato rilasciato figurerà nel rapporto.';
$string['removecert'] = 'Certificati rilasciati rimossi';
$string['report'] = 'Rapporto';
$string['reportcert'] = 'Rapporto Certificati';
$string['reportcert_help'] = 'Scegliendo Si qui allora la data di rilascio del certificato, il codice e il nome del corso saranno mostrati nel rapporto certificati utente. Scegliendo di stampare la valutazione anche questa verrà mostrata nel rapporto.';
$string['reviewcertificate'] = 'Rivedi Certificato';
$string['savecert'] = 'Salva Certificato';
$string['savecert_help'] = 'Scegliendo questa opzione una copia di ogni certificato utente verrà salvata nella cartella moddata dell\'attività Certificato corrispondente. Un collegamento al certificato rilasciato verrà stampato nel rapporto certificati.';
$string['sigline'] = 'linea';
$string['statement'] = 'ha completato il corso';
$string['summaryofattempts'] = 'Sommario dei Certificati ricevuto precedentemente';
$string['textoptions'] = 'Opzioni Testo';
$string['title'] = 'CERTIFICATO';
$string['to'] = 'Conferito a ';
$string['typeA4_embedded'] = 'A4 Incapsulato';
$string['typeA4_non_embedded'] = 'A4 Non-Incapsulato';
$string['typeletter_embedded'] = 'Lettera Incapsulata';
$string['typeletter_non_embedded'] = 'Lettera Non-Incapsulata';
$string['userdateformat'] = 'Formato Data Utente';
$string['validate'] = 'Verifica';
$string['verifycertificate'] = 'Verifica Certificato';
$string['viewcertificateviews'] = 'Vedi {$a} Certificati rilasciati';
$string['viewed'] = 'Hai ricevuto questo certificato il:';
$string['viewtranscript'] = 'Vedi Certificati';
