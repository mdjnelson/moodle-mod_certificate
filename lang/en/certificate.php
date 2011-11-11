<?PHP // $Id: certificate.php,v 2.0 2010/09/20

// General functions
$string['pluginname'] = 'Certificate';
$string['pluginadministration'] = 'Certificate administration';
$string['modulename'] = 'Certificate';
$string['modulenameplural'] = 'Certificates';
$string['certificatename'] = 'Certificate Name';
$string['certificate:view'] = 'View Certificate';
$string['certificate:manage'] = 'Manage Certificate';
$string['certificate:printteacher'] = 'Print Teacher';
$string['certificate:student'] = 'Get Certificate';

// Adding an instance
$string['intro'] = 'Introduction';
$string['areaintro'] = 'Certificate introduction';
$string['addlinklabel'] = 'Add another linked activity option';
$string['addlinktitle'] = 'Click to add another linked activity option';
$string['issueoptions'] = 'Issue Options';
$string['textoptions'] = 'Text Options';
$string['designoptions'] = 'Design Options';
$string['lockingoptions'] = 'Locking Options';
$string['certificatetype'] = 'Certificate Type';
$string['completiondate'] = 'Course Completion';
$string['emailteachers'] = 'Email Teachers';
$string['emailothers'] = 'Email Others';
$string['savecert'] = 'Save Certificates';
$string['reissuecert'] = 'Reissue Certificates';
$string['delivery'] = 'Delivery';
$string['download'] = 'Force download';
$string['openbrowser'] = 'Open in new window';
$string['emailcertificate'] = 'Email (Must also choose save!)';
$string['letter'] = 'Letter';
$string['orientation'] = 'Orientation';
$string['landscape'] = 'Landscape';
$string['portrait'] = 'Portrait';
$string['border'] = 'Border';
$string['borderstyle'] = 'Border Image';
$string['borderlines'] = 'Lines';
$string['bordercolor'] = 'Border Lines';
$string['borderblack'] = 'Black';
$string['borderbrown'] = 'Brown';
$string['borderblue'] = 'Blue';
$string['bordergreen'] = 'Green';
$string['printwmark'] = 'Watermark Image';
$string['datehelp'] = 'Date';
$string['datefmt'] = 'Date Format';
$string['userdateformat'] = 'User\'s Language Date Format';
$string['receiveddate'] = 'Date Received';
$string['issueddate'] = 'Date Issued';
$string['gradedate'] = 'Grade Date';
$string['printnumber'] = 'Print Code';
$string['printgrade'] = 'Print Grade';
$string['printoutcome'] = 'Print Outcome';
$string['nogrades'] = 'No grades available';
$string['gradefmt'] = 'Grade Format';
$string['gradepercent'] = 'Percentage Grade';
$string['gradepoints'] = 'Points Grade';
$string['gradeletter'] = 'Letter Grade';
$string['printhours'] = 'Print Credit Hours';
$string['printsignature'] = 'Signature Image';
$string['sigline'] = 'line';
$string['printteacher'] = 'Print Teacher Name(s)';
$string['customtext'] = 'Custom Text';
$string['printdate'] = 'Print Date';
$string['printseal'] = 'Seal or Logo Image';

// Reset certificates
$string['deletissuedcertificates'] = 'Delete issued certificates';
$string['removecert'] = 'Issued certificates removed';

// Help strings
$string['emailteachers_help'] = 'If enabled, then teachers are alerted with an email whenever students receive a certificate.';
$string['emailothers_help'] = 'Enter the email addresses here, separated by a comma, of those who should be alerted with an email whenever students receive a certificate.';
$string['delivery_help'] = 'Choose here how you would like your students to get their certificate.
Open in Browser: Opens the certificate in a new browser window.
Force Download: Opens the browser file download window.
Email Certificate: Choosing this option sends the certificate to the student as an email attachment.
After a user receives their certificate, if they click on the certificate link from the course homepage, they will see the date they received their certificate and will be able to review their received certificate.';
$string['savecert_help'] = 'If you choose this option, then a copy of each user\'s certificate pdf file is saved in the course files moddata folder for that certificate. A link to each user\'s saved certificate will be displayed in the certificate report.'; 
$string['reportcert_help'] = 'If you choose yes here, then this certificate\'s date received, code number, and the course name will be shown on the user certificate reports.  If you choose to print a grade on this certificate, then that grade will also be shown on the certificate report.';
$string['reissuecert_help'] = 'If you choose yes here, then this certificate will be reissued with a new date, grade and code number every time a user clicks on the certificate link. Note:  Although a table will show their past received dates, no review button will be available to users.  Only the latest issued certificate will appear in the certificate report.';
$string['printdate_help'] = 'This is the date that will be printed, if a print date is selected. If the course completion date is selected but the student has not completed the course, the date received will be printed. You can also choose to print the date based on when an activity was graded. If a certificate is issued before that activity is graded, the date received will be printed.';
$string['datefmt_help'] = 'Choose a date format to print the date on the certificate. Or, choose the last option to have the date printed in the format of the user\'s chosen language.';
$string['printnumber_help'] = 'A unique 10-digit code of random letters and numbers can be printed on the certificate. This number can then be verified by comparing it to the code number displayed in the certificates report.';
$string['printgrade_help'] = 'You can choose any available course grade items from the gradebook to print the user\'s grade received for that item on the certificate.  The grade items are listed in the order in which they appear in the gradebook. Choose the format of the grade below.'; 
$string['gradefmt_help'] = 'There are three available formats if you choose to print a grade on the certificate:

Percentage Grade: Prints the grade as a percentage.
Points Grade: Prints the point value of the grade.
Letter Grade: Prints the percentage grade as a letter.';  
$string['printoutcome_help'] = 'You can choose any course outcome to print the name of the outcome and the user\'s received outcome on the certificate.  An example might be: Assignment Outcome: Proficient.';
$string['printhours_help'] = 'Enter here the number of credit hours to be printed on the certificate.';
$string['printteacher_help'] = 'For printing the teacher name on the certificate, set the role of teacher at the module level.  Do this if, for example, you have more than one teacher for the course or you have more than one certificate in the course and you want to print different teacher names on each certificate.  Click to edit the certificate, then click on the Locally assigned roles tab.  Then assign the role of Teacher (editing teacher) to the certificate (they do not HAVE to be a teacher in the course--you can assign that role to anyone).  Those names will be printed on the certificate for teacher.';
$string['customtext_help'] = 'If you want the certificate to print different names for the teacher than those who are assigned
the role of teacher, do not select Print Teacher or any signature image except for the line image.  Enter the teacher names in this text box as you would like them to appear.  By default, this text is placed in the lower left of the certificate. The following html tags are available: &lt;br&gt;, &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;img&gt; (src and width (or height) are mandatory), &lt;a&gt; (href is mandatory), &lt;font&gt; (possible attributes are: color, (hex color code), face, (arial, times, courier, helvetica, symbol)).'; 
$string['certificatetype_help'] = 'This is where you determine the layout of the certificate. The certificate type folder includes four default certificates:
A4 Embedded prints on A4 size paper with embedded font.
A4 Non-Embedded prints on A4 size paper without embedded fonts.
Letter Embedded prints on letter size paper with embedded font.
Letter Non-Embedded prints on letter size paper without embedded fonts.

The non-embedded types use the Helvetica and Times fonts.  If you feel your users will not have these fonts on their computer, or if your language uses characters or symbols that are not accommodated by the Helvetica and Times fonts, then choose an embedded type.  The embedded types use the Dejavusans and Dejavuserif fonts.  This will make the pdf files rather large; thus it is not recommended to use an embedded type unless you must.

New type folders can be added to the certificate/type folder. The name of the folder and any new language strings for the new type must be added to the certificate language file.';
$string['orientation_help'] = 'Choose whether you want your certificate orientation to be portrait or landscape.';
$string['borderstyle_help'] = 'The Border Image option allows you to choose a border image from the certificate/pix/borders folder.  Select the border image that you want around the certificate edges or select no border.';
$string['bordercolor_help'] = 'Since images can substantially increase the size of the pdf file, you may choose to print a border of lines instead of using a border image (be sure the Border Image option is set to No).  The Border Lines option will print a nice border of three lines of varying widths in the chosen color.';
$string['printwmark_help'] = 'A watermark file can be placed in the background of the certificate. A watermark is a faded graphic. A watermark could be a logo, seal, crest, wording, or whatever you want to use as a graphic background.';
$string['printsignature_help'] = 'This option allows you to print a signature image from the certificate/pix/signatures folder.  You can print a graphic representation of a signature, or print a line for a written signature. By default, this image is placed in the lower left of the certificate.';
$string['printseal_help'] = 'This option allows you to select a seal or logo to print on the certificate from the certificate/pix/seals folder. By default, this image is placed in the lower right corner of the certificate.';

// Strings for verification block
$string['entercode'] = 'Enter certificate code to verify:';
$string['validate'] = 'Verify';
$string['certificate'] = 'Verification for certificate code:';
$string['verifycertificate'] = 'Verify Certificate';
$string['notfound'] = 'The certificate number could not be validated.';
$string['back'] = 'Back';
$string['to'] = 'Awarded to';
$string['course'] = 'For';
$string['date'] = 'On';
$string['grade'] = 'Grade';

// Certificate view, index, report strings
$string['incompletemessage'] = 'In order to download your certificate, you must first complete all required '.'activities. Please return to the course to complete your coursework.';
$string['awardedto'] = 'Awarded To';
$string['issued'] = 'Issued';
$string['notissued'] = 'Not Issued';
$string['notissuedyet'] = 'Not issued yet';
$string['notreceived'] = 'You have not received this certificate';
$string['getcertificate'] = 'Get your certificate';
$string['report'] = 'Report';
$string['code'] = 'Code';
$string['viewed'] = 'You received this certificate on:';
$string['lastviewed'] = 'You last received this certificate on:';
$string['viewcertificateviews'] = 'View {$a} issued certificates';
$string['reviewcertificate'] = 'Review your certificate';
$string['openwindow'] = 'Click the button below to open your certificate
in a new browser window.';
$string['opendownload'] = 'Click the button below to save your certificate
to your computer.';
$string['openemail'] = 'Click the button below and your certificate
will be sent to you as an email attachment.';
$string['receivedcerts'] = 'Received certificates';
$string['summaryofattempts'] = 'Summary of Previously Received Certificates';

// Email text
$string['emailstudenttext'] = 'Attached is your certificate for {$a->course}.';
$string['awarded'] = 'Awarded';
$string['emailteachermail'] = '
{$a->student} has received their certificate: \'{$a->certificate}\'
for {$a->course}.

You can review it here:

    {$a->url}';
$string['emailteachermailhtml'] = '
{$a->student} has received their certificate: \'<i>{$a->certificate}</i>\'
for {$a->course}.

You can review it here:

    <a href=\"{$a->url}\">Certificate Report</a>.';

// Names of type folders
$string['typeA4_embedded'] = 'A4 Embedded';
$string['typeletter_embedded'] = 'Letter Embedded';
$string['typeA4_non_embedded'] = 'A4 Non-Embedded';
$string['typeletter_non_embedded'] = 'Letter Non-Embedded';

// Print to certificate strings
$string['grade'] = 'Grade';
$string['coursegrade'] = 'Course Grade';
$string['credithours'] = 'Credit Hours';

$string['title'] = 'CERTIFICATE of ACHIEVEMENT';
$string['certify'] = 'This is to certify that';
$string['statement'] = 'has completed the course';

// Certificate transcript strings
$string['notapplicable'] = 'N/A';
$string['certificatesfor'] = 'Certificates for';
$string['coursename'] = 'Course';
$string['viewtranscript'] = 'View Certificates';
$string['mycertificates'] = 'My Certificates';
$string['nocertificatesreceived'] = 'has not received any course certificates.';
$string['notissued'] = 'Not received';
$string['reportcert'] = 'Report Certificates';
$string['certificatereport'] = 'Certificates Report';
$string['printerfriendly'] = 'Printer-friendly page';
$string['or'] = 'Or';
