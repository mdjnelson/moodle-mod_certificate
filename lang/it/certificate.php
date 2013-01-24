<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings for the certificate module
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @translation Goran Josic <goran.josic@usi.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addlinklabel'] = 'Aggiungi un\'altra opzione attività collegata';
$string['addlinktitle'] = 'Clicca per aggiungere un\'altra opzione attività collegata';
$string['areaintro'] = 'Introduzione Certificato';
$string['awarded'] = 'Conferito';
$string['awardedto'] = 'Conferito a';
$string['back'] = 'Indietro';
$string['border'] = 'Bordo';
$string['borderblack'] = 'Nero';
$string['borderblue'] = 'Blu';
$string['borderbrown'] = 'Marrone';
$string['bordercolor'] = 'Bordo Linee';
$string['bordercolor_help'] = 'Dal momento che le immagini possono aumentare il peso del file pdf è possibile scegliere di stampare linee ai bordi invece di usare delle immagini (assicurarsi che l\'opzione Bordo Immagine sia impostata su No). L\'opzione Bordo Linee stamperanno il bordo di tre linee variandone lo spessore e usando il colore scelto.';
$string['bordergreen'] = 'Verde';
$string['borderlines'] = 'Linee';
$string['borderstyle'] = 'Bordo Immagine';
$string['borderstyle_help'] = 'L\'opzione Bordo Immagine permette di usare una immagine come bordo. Le immagine sono scelte dalla cartella  certificate/pix/borders. Selezionare il bordo immagine preferito oppure selezionare nessuno bordo.';
$string['certificate'] = 'Verifica del codice certificato:';
$string['certificate:addinstance'] = 'Aggiungi un\'istanza del certificato';
$string['certificate:manage'] = 'Gestisci l\'istanza del certificato';
$string['certificate:printteacher'] = 'Aggiunge il nome del docente al certificato se l\'impostazione è attiva';
$string['certificate:student'] = 'Ritrova il certificato';
$string['certificate:view'] = 'Vedi il certificato';
$string['certificatename'] = 'Nome Certificato';
$string['certificatereport'] = 'Rapporto Certificato';
$string['certificatesfor'] = 'Certificato per';
$string['certificatetype'] = 'Tipo di Certificato';
$string['certificatetype_help'] = 'Qui si determina la disposizione del certificato. Ci sono quattro disposizione predefinite:
A4 Incluso stampa su pagina grandezza A4 includendo i font.
A4 Non-Incluso stampa su pagina grandezza A4 senza includere i font.
Lettera Incluso stampa su pagina grandezza lettera con fonti inclusi.
Lettera Non-Incluso stampa su pagina grandezza lettera con fonti non inclusi.

I tipi Non-Incluso usano i font Helvetica e Times. Se non si è sicuri che questi font siano presenti sulle macchine degli utenti scegliere le opzioni Incluso. In questo caso i font inclusi saranno Dejavusans e Dejavuserif. Questo renderà il file più pesante quindi non è la scelta raccomandata se non ne esiste il bisogno.

Nuovi tipo possono essere aggiunti alla cartella certificate/type. Il nome della cartella e ogni nuova voce dovrebbe essere aggiunta nei file lingue del certificato.';
$string['certify'] = 'Questo documento certifica che';
$string['code'] = 'Codice';
$string['completiondate'] = 'Completamento Corso';
$string['course'] = 'Per';
$string['coursegrade'] = 'Voto Corso';
$string['coursename'] = 'Corso';
$string['coursetimereq'] = 'Tempo richiesto in minuti';
$string['coursetimereq_help'] = 'Inserire qui il tempo minimo in minuti richiesto all\'utente prima che il certificato venga rilasciato.';
$string['credithours'] = 'Ore Credito';
$string['customtext'] = 'Testo Personalizzato';
$string['customtext_help'] = 'Se si vuole che il nome docente specificato sul certificato sia differente dai nomi di utenti che nel corso hanno il ruolo di docente allora non selezionare Stampa Docente o qualsiasi altra immagine firma. Aggiungere la voce del docente in questa casella specificando delle direttive su come disporre il nome. La posizione predefinita è in basso a sinistra. I seguenti tag html sono disponibili: &lt;br&gt;, &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;img&gt; (src e width (oppure height) sono obbligatori), &lt;a&gt; (href non è obbligatorio), &lt;font&gt; (possibili attributi sono: color, (codici colore esadecimali), face, (arial, times, courier, helvetica, symbol)).';
$string['date'] = 'Giorno';
$string['datefmt'] = 'Formato Data';
$string['datefmt_help'] = 'Scegliere il formato data da stampare sul certificato. Altrimenti, scegliere l\'ultima opzione per avere la data stampata secondo la localizzazione scelta dall\'utente.';
$string['datehelp'] = 'Data';
$string['deletissuedcertificates'] = 'Cancella il certificato rilasciato';
$string['delivery'] = 'Consegna';
$string['delivery_help'] = '
Scegliere qui la modalità di consegna del certificato:

Apri nel Browser: Apre il certificato in una nuova finestra.
Forza Download: Apre il la finestra Download del browser.
Spedisci via e-mail: Scegliere questa opzione per spedire il certificato via e-mail agli studenti.
Dopo che l\'utente ha ricevuto il certificato se clicca sulla risorsa certificato dalla pagina del corso vedrà la data del rilascio del certificato e potrà rivedere il certificato.';
$string['designoptions'] = 'Opzioni di design';
$string['download'] = 'Forza Download';
$string['emailcertificate'] = 'Email (Selezionare anche Salva!)';
$string['emailothers'] = 'Email: Spedisci ad altri';
$string['emailothers_help'] = 'Aggiungere qui, separati da virgole, gli indirizzi delle persone che riceveranno le notifiche del rilascio del certificato.';
$string['emailstudenttext'] = 'Il certificato in allegato è per il corso {$a->course}.';
$string['emailteachers'] = 'Email: Avvisa Docenti';
$string['emailteachers_help'] = 'Se abilitato avvisa i docenti via email ogni volta che il certificato viene rilasciato.';
$string['emailteachermail'] = '
{$a->student} ha ricevuto il certificato: \'{$a->certificate}\'
per il corso {$a->course}.

Una anteprima del certificato è disponibile qui:

    {$a->url}';
$string['emailteachermailhtml'] = '
{$a->student} ha ricevuto il certificato: \'<i>{$a->certificate}</i>\'
per il corso {$a->course}.

Una anteprima del certificato è disponibile qui:

    <a href="{$a->url}">Rapporto Certificato</a>.';
$string['entercode'] = 'Inserire il codice certificato per la verifica:';
$string['getcertificate'] = 'Scarica il certificato';
$string['grade'] = 'Voto';
$string['gradedate'] = 'Data Voto';
$string['gradefmt'] = 'Formato Voto';
$string['gradefmt_help'] = '
Sono disponibili tre formati per la stampo del voto sul certificato:

Voto Percentuale: Stampa il voto in percentuale.
Voto Punti: Stampa il voto in punti.
Voto Lettera: Stampa il valore del voto sotto forma di lettera.';
$string['gradeletter'] = 'Voto Lettera';
$string['gradepercent'] = 'Voto Percentuale';
$string['gradepoints'] = 'Voto Lettera';
$string['imagetype'] = 'Tipo Immagine';
$string['protected'] = 'Limita al corso';
$string['courseshortname'] = 'Nome abbreviato del corso';
$string['novalidcourseshortname'] = 'Il nome abbreviato del corso non è valido. Nessun corso ha questo nome. Controllare per favore.';
$string['missingcourseshortname'] = 'Specificare il nome abbreviato del corso per favore.';
$string['incompletemessage'] = 'Per poter scaricare il certificato bisogna prima completare tutte le attività richieste. Ritornare, per favore, al corso e completare quanto richiesto.';
$string['intro'] = 'Introduzione';
$string['issueoptions'] = 'Opzioni Rilascio';
$string['issued'] = 'Rilasciato';
$string['issueddate'] = 'Data Rilascio';
$string['landscape'] = 'Orizzontale';
$string['lastviewed'] = 'Ultimo rilascio del certificato:';
$string['letter'] = 'Lettera';
$string['lockingoptions'] = 'Opzioni di protezione';
$string['modulename'] = 'Certificato';
$string['modulenameplural'] = 'Certificati';
$string['mycertificates'] = 'I miei Certificati';
$string['nocertificates'] = 'Non ci sono certificati';
$string['nocertificatesissued'] = 'Nessun Certificato rilasciato';
$string['nocertificatesreceived'] = 'non ha ricevuto certificati.';
$string['nofileselected'] = 'Bisogna scegliere un file valido per il caricamento!';
$string['nogrades'] = 'Nessun voto disponibile';
$string['notapplicable'] = 'N/A';
$string['notfound'] = 'Il numero certificato non è valido.';
$string['notissued'] = 'Non Rilasciato';
$string['notissuedyet'] = 'Non ancora rilasciato';
$string['notreceived'] = 'Certificato non ancora ricevuto';
$string['openbrowser'] = 'Apri in una nuova finestra';
$string['opendownload'] = 'Cliccare il bottone sottostante per salvare il certificato.';
$string['openemail'] = 'Cliccare il bottone sottostante per ricevere il certificato sotto forma di allegato email.';
$string['openwindow'] = 'Cliccare il bottone sottostante per aprire il certificato in una nuova finestra.';
$string['or'] = 'Oppure';
$string['orientation'] = 'Orientamento';
$string['orientation_help'] = 'Scegliere orientamento del certificato tra verticale e orizzontale.';
$string['pluginadministration'] = 'Amministrazione Certificato';
$string['pluginname'] = 'Certificato';
$string['portrait'] = 'Verticale';
$string['printdate'] = 'Stampa Data';
$string['printdate_help'] = '
La data verrà stampata se l\'opzione Stampa Data è stata selezionata. Se la data del completamento del corso è selezionata e lo studente non ha ancora completato il corso verrà stampata la data del rilascio del certificato. È inoltre possibile scegliere la data della assegnazione del voto di una delle attività. Se il certificato è rilasciato prima del completamento della attività scelta allora verrà stampata la data di rilascio.';
$string['printerfriendly'] = 'Pagina ottimizzata per la stampa';
$string['printhours'] = 'Stampa Ore Credito';
$string['printhours_help'] = 'Aggiungere qui il numero di Ore Credito da stampare sul certificato.';
$string['printgrade'] = 'Stampa Voto';
$string['printgrade_help'] = 'È possibile stampare uno qualsiasi dei voti nella tabella Valutazioni. I voti sono elencati in ordine nel quale appaiono nella tabella Valutazioni. Scegliere il formato di stampa da usare sotto.';
$string['printnumber'] = 'Stampa Codice';
$string['printnumber_help'] = 'Codice univoco di 10 cifre composto da una sequenza casuale di numeri e lettere può essere aggiunta al certificato. Questo numero può essere verificato nel rapporto dei certificati rilasciati.';
$string['printoutcome'] = 'Stampa Risultato';
$string['printoutcome_help'] = 'È possibile stampare il risultati ottenuti dall\'utente riferiti alle attività. Un esempio sarebbe: Risultati Compito: Competente.';
$string['printseal'] = 'Sigillo o Immagine Logo';
$string['printseal_help'] = 'Questa opzione permette di selezionare una immagine o un sigillo da aggiungere al certificato. La posizione predefinita è angolo in basso a destra. Le immagini sono scelte dalla cartella certificate/pix/seals.';
$string['printsignature'] = 'Immagine Firma';
$string['printsignature_help'] = 'Questa opzione permette di aggiungere al certificato una immagine firma. La posizione predefinita è in basso a sinistra. È possibile stampare una immagine oppure una riga per la firma. Le immagini sono scelta dalla cartella certificate/pix/signatures.';
$string['printteacher'] = 'Stampa Nome/i Docente/i';
$string['printteacher_help'] = 'Per stampare i nomi dei docenti è anche possibile impostare i ruoli al livello del modulo. Fare questo, per esempio, quando si ha più di un docente nel corso o si ha più di un certificato rilasciato e si vuole stampare nomi di docente differenti per ogni certificato rilasciato. Per assegnare il docente al modulo attivare la modalità di modifica della istanza del Certificato e scegliere poi Ruoli assegnati localmente. Assegnare quindi il ruolo di docente alla istanza (non è necessario che l\'utente scelto sia docente del corso).';
$string['printwmark'] = 'Immagine Filigrana';
$string['printwmark_help'] = 'È possibile impostare una immagine filigrana. Una filigrana è una immagine di sfondo sfumata. La filigrana può essere un logo, un sigillo, uno stemma, delle parole o qualsiasi altra immagine addatta come sfondo del documento..';
$string['receivedcerts'] = 'Certificati ricevuti';
$string['receiveddate'] = 'Data Ricezione';
$string['removecert'] = 'Certificati rilasciati rimossi';
$string['report'] = 'Rapporto';
$string['reportcert'] = 'Rapporto Certificati';
$string['reportcert_help'] = 'Scegliendo Si per questa opzione aggiungerà la data di ricezione, il codice e il nome del corso al rapporto certificati. Scegliendo di stampare il voto sul certificato anche questo verrà incluso nel rapporto.';
$string['requiredtimenotmet'] = 'È richiesto un minimo di {$a->requiredtime} minuti di partecipazione prima di poter ricevere il certificato';
$string['requiredtimenotvalid'] = 'Il tempo richiesto deve essere un numero maggiore di 0';
$string['reviewcertificate'] = 'Rivedi il certificato';
$string['savecert'] = 'Salva Certificati';
$string['savecert_help'] = 'Scegliendo questa opzione una copia di ogni certificato rilasciato verrà salvata dentro la cartella moddata del corso. Il collegamento al certificato verrà aggiunto nel rapporto certificati.';
$string['seal'] = 'Sigillo';
$string['sigline'] = 'linea';
$string['signature'] = 'Firma';
$string['statement'] = 'ha completato il corso';
$string['summaryofattempts'] = 'Sommario dei Certificati ricevuti precedentemente';
$string['textoptions'] = 'Opzioni testo';
$string['title'] = 'CERTIFICATO';
$string['to'] = 'Assegnato a';
$string['typeA4_embedded'] = 'A4 Incluso';
$string['typeA4_non_embedded'] = 'A4 Non-Incluso';
$string['typeletter_embedded'] = 'Lettera Incluso';
$string['typeletter_non_embedded'] = 'Lettera Non-Incluso';
$string['unsupportedfiletype'] = 'Il file deve essere di tipo jpeg o png';
$string['uploadimage'] = 'Carica immagine';
$string['uploadimagedesc'] = 'Questo bottone aprirà una nuova schermata nella quale sarà possibile caricare immagini.';
$string['userdateformat'] = 'Formato Data nella lingua utente';
$string['validate'] = 'Verifica';
$string['verifycertificate'] = 'Verifica Certificato';
$string['viewcertificateviews'] = 'Vedi {$a} certificati rilasciati';
$string['viewed'] = 'Certificato rilasciato il:';
$string['viewtranscript'] = 'Vedi Certificati';
$string['watermark'] = 'Filigrana';
