<?php

/**
* Language strings for the certificate module
*
* @package mod
* @subpackage certificate
* @copyright 2012 Ale Vilar <alevilar@gmail.com>
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

$string['addlinklabel'] = 'Agregar otro opciones de actividad condicional';
$string['addlinktitle'] = 'Clickea para agregar otra opción de actividad condicional';
$string['areaintro'] = 'Introducción del Certificado';
$string['awarded'] = 'Otorgado';
$string['awardedto'] = 'Otorgado A';
$string['back'] = 'Volver';
$string['border'] = 'Borde';
$string['borderblack'] = 'Negro';
$string['borderblue'] = 'Azul';
$string['borderbrown'] = 'Marron';
$string['bordercolor'] = 'Líneas de Borde';
$string['bordercolor_help'] = 'Las imágenes pueden incrementar considerablemente el tamaño del PDF, quizás debas escoger líneas para los bordes en lugar de imágenes. Para ello debes asegurarte que "Imagen del Borde" este seleccionado como "NO"';
$string['bordergreen'] = 'Verde';
$string['borderlines'] = 'Lineas';
$string['borderstyle'] = 'Imagen del Borde';
$string['borderstyle_help'] = 'La Imagen del Borde permite escoger una Imagen de la carpeta certificate/pix/borders.';
$string['certificate:view'] = 'Ver Certificado';
$string['certificate:manage'] = 'Administrar Certificados';
$string['certificate:printteacher'] = 'Imprimir Profesor';
$string['certificate:student'] = 'Obten el Certificado';
$string['certificate'] = 'Verificación para el código del Certificado:';
$string['certificatename'] = 'Nombre del Certificado';
$string['certificatereport'] = 'Reportes de Certificados';
$string['certificatesfor'] = 'Certificado Para';
$string['certificatetype'] = 'Tipo de Certificado';
$string['certificatetype_help'] =
'Aca es donde se determina la dispoción y forma del Certificado. El tipo de carpeta incluye 4 certificados por default:
A4 Embebido imprime tamaño A4 con la fuente (tipografia) embebida.
A4 No-Embebido imprime tamaño A4 sin embeber las tipografia.
Letter Embebido imprime tamaño Letter embebiendo la tipografia.
Letter No-Embebido imprime Letter sin embeber la tipografia.

Los tipos no-embebidos usan las fuente: Helvetica y Time New Roman. Si consideras que los usuarios no van a tener estas fuentes instaladas en sus computadoras, entonces debes escoger la opción de "tipografía embebida". Las fuentes embebidas son: Dejavusans y Dejavuserif. Esto hará que el PDF sea más pesado, pero asegura su correcta visualización.

Pueden ser agregados nuevos tipos dentro de la carpeta certificate/type folder. El nombre de la carpeta y cada nuevo string de lenguaje debe ser agregado en el archivo de lenguajes del módulo certificado.';
$string['certify'] = 'Por la siguiente certifico que';
$string['code'] = 'Código';
$string['completiondate'] = 'Nivel de Avance del Curso';
$string['course'] = 'Para';
$string['coursegrade'] = 'Calificación del curso';
$string['coursename'] = 'Curso';
$string['credithours'] = 'Horas Acreditadas';
$string['customtext'] = 'Texto por default';
$string['customtext_help'] = 'Si querés que el certificado imprima distintos nombres de profesores en lugar de aquellos que estan designados con el rol de profesor, no selecciones "Imprimir Profesor". Ingresa el nombre de los docentes según como quieres que aparezcan. Por defecto el texto será ubicado abajo a la izquierda del certificado. Lo siguientes campos HTML son válidos: &lt;br&gt;, &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;img&gt; (src and width (or height) are mandatory), &lt;a&gt; (href is mandatory), &lt;font&gt; (possible attributes are: color, (hex color code), face, (arial, times, courier, helvetica, symbol)).';
$string['date'] = 'On';
$string['datefmt'] = 'Formato de Fecha';
$string['datefmt_help'] = 'Seleccione un formato de fecha para mostrarlo en el certificado. O, seleccione la última opción para que se imprima en el formato en el que el usuario tiene el lenguaje definido.';
$string['datehelp'] = 'Fecha';
$string['deletissuedcertificates'] = 'Eliminar certificados emitidos';
$string['delivery'] = 'Envío';
$string['delivery_help'] = 'Seleccione aquí la forma en la que el estudiante obtendrá el certificado.
Abrir en el Browser: Abre el certificado en una nueva ventana.
Forzar Descarga: Abre la ventana de descarga de archivos del browser.
Enviar por Email: Eligiendo esta opción la envia como un adjunto en un email..
Luego de que el usuario reciba su certificado, si cliquea el link del certificado desde la pagina del curso, verá la fecha en la que recibió el certificado y podrá revisar su certificado recibido.';
$string['designoptions'] = 'Opciones de Diseño';
$string['download'] = 'FOrzar Descarga';
$string['emailcertificate'] = 'Email (Luego deberá apretar en Guardar!)';
$string['emailothers'] = 'Enviar Email a Otros';
$string['emailothers_help'] = 'Ingrese todos los emails que desea enviar el certificado separándolos con una coma para que sean alertados las personas cada vez que un usuario recibió su certificado';
$string['emailstudenttext'] = 'Se adjuntó el certificado para {$a->course}.';
$string['emailteachers'] = 'Email a Profesores';
$string['emailteachers_help'] = 'Si esta activado, los profesores serán alertados con un email cada vez que los estudiantes reciban el certificado.';
$string['emailteachermail'] = '
{$a->student} ha recibido su certificado: \'{$a->certificate}\'
para {$a->course}.

Lo puedes ver en el siguiente link:

{$a->url}';
$string['emailteachermailhtml'] = '
{$a->student} ha recibido su sertificado: \'<i>{$a->certificate}</i>\'
para {$a->course}.

Lo puedes ver y descargar en el siguiente link:

<a href="{$a->url}">Certificado</a>.';
$string['entercode'] = 'Ingrese el código del certificado para validar:';
$string['getcertificate'] = 'Obten tu certificado';
$string['grade'] = 'Calificación';
$string['gradedate'] = 'Fecha de Calificación';
$string['gradefmt'] = 'Formato de Calificación';
$string['gradefmt_help'] = 'Existen tres formatos disponibles si escoges mostrar la calificacion en el certificado:

Calificación Porcentual: Imprime la calificación como un porcentaje.
Calificación Numérica: Imprime el valor numérico de la calificación.
Calificación por Letra: Imprime el porcentaje de calificación como una letra.';
$string['gradeletter'] = 'Calificación por Letras';
$string['gradepercent'] = 'Calificación Porcentual';
$string['gradepoints'] = 'Calificación Numérica';
$string['incompletemessage'] = 'Para que puedas descargar el certificado, primero debes completar todas las actividades requericas. Por favor, regresa al curso para completar tu trabajo.';
$string['intro'] = 'Introducción';
$string['issueoptions'] = 'Opciones de los Cuestionados';
$string['issued'] = 'Emitido';
$string['issueddate'] = 'Fecha de emisión';
$string['landscape'] = 'Porta retratos';
$string['lastviewed'] = 'Tu ultimo certificado recibido fue:';
$string['letter'] = 'Letter';
$string['lockingoptions'] = 'Opciones de Bloqueo';
$string['modulename'] = 'Certificado';
$string['modulenameplural'] = 'Certificados';
$string['mycertificates'] = 'Mis Certificados';
$string['nocertificates'] = 'No hay certificados';
$string['nocertificatesissued'] = 'No hay certificados que hayan sido emitidos';
$string['nocertificatesreceived'] = 'No se recibieron certificados de cursos.';
$string['nogrades'] = 'No hay calificaciones disponibles';
$string['notapplicable'] = 'N/A';
$string['notfound'] = 'No es obligatorio validad el número de certificado.';
$string['notissued'] = 'No Emitido';
$string['notissuedyet'] = 'Aún no fue emitido';
$string['notreceived'] = 'Tu no has recibido este certificado';
$string['openbrowser'] = 'Abrir en nueva ventana';
$string['opendownload'] = 'Clickeá el boton de abajo para guardar el certificado en la computadora.';
$string['openemail'] = 'Clickeá el boton de abajo y tu certificado te será enviado vía email como un adjunto.';
$string['openwindow'] = 'Clickeá el boton de abajo para abrir el certificado en una nueva ventana.';
$string['or'] = 'O';
$string['orientation'] = 'Orientación';
$string['orientation_help'] = 'Seleccine si desea que la orientación del certificado sea orizontal o vertical.';
$string['pluginadministration'] = 'Administración del Certificado';
$string['pluginname'] = 'Certificado';
$string['portrait'] = 'Porta Retrato (Horizontal)';
$string['printdate'] = 'Fecha de impresión';
$string['printdate_help'] = 'Esta será la fecha en el que será mostrado, si se selecciona una fecha de impresión pero el estudiante no completó el curso, la fecha de recepción será impresa. Tu puedes elegir mostrar la fecha basada en alguna actividad en especial o cuando se haya alcanzado alguna calificación determinada, en ese caso esa será la fecha que se mostrará.';
$string['printerfriendly'] = 'Página de impresión segura';
$string['printhours'] = 'Mostrar Horas de Crédito';
$string['printhours_help'] = 'Ingrese aqui el número de horas acreditadas que serán mostradas en el certificado.';
$string['printgrade'] = 'Mostrar Calificación';
$string['printgrade_help'] = 'Puedes elegir entre las calificaciones disponibles para mostrar la calificación del usuario recibida para ese ítem en el certificado. Los items de calificación son listados en el orden en el que aparecen. Seleccione el formato de calificaciones del listado de aqui abajo';
$string['printnumber'] = 'Mostrar código';
$string['printnumber_help'] = 'Un código aleatorio de 10 dígitos y letras puede ser impreso en el certificado. Este número pued ser verificado más adelante comparando con el reporte de códigos de certificados.';
$string['printoutcome'] = 'Imprimir los resultados';
$string['printoutcome_help'] = 'Puedes elegir distintas nombres de resultados u objetivos para imprimir junto al nombre de usuario en el certificado. Un ejemplo puede ser: Resultado en la Asignatura: Aprobado.';
$string['printseal'] = 'Sello o Imágen del Logo';
$string['printseal_help'] = 'Esta opción permite seleccionar un sello o logo que será mostrado en el certificado y lo extrae de la carpeta certificate/pix/seals. Por defecto esta imagen esta ubicada en el costado inferior derecho del certificado.';
$string['printsignature'] = 'Imagen de la firma';
$string['printsignature_help'] = 'Esta opción permite imprimir una firma en el certificado. La firma la lee de la carpeta certificate/pix/signatures. Tu puedes imprimir una representación gráfica de la firma, o imprimir una firma para una asignatura en especial. Por defecto esta imagen es ubicada en el borde inberior izquierdo del certificado.';
$string['printteacher'] = 'Imprimir el nombre o nombres de los profesores';
$string['printteacher_help'] = 'Para imprimir el nombre del profesor en el certificado, setea el rol del profesor a nivel de módulo. Esto debes hacerlo si, por ejemplo, tienes más de un profesor por curso o más de un certificado en el curso y quieres imprimir diferentes nombres de profesores en cada certificado. Clickea para editar el certificado, luego en la solapa de roles asignados localmente. Luego asignar roles de profesor (con permisos de edición) al certificado (ellos no necesariamente TIENEN que ser profesores del curso, podrias asignarle ese rol a cualquiera). Esos nombres serán impresos en el certificado por cada profesor.';
$string['printwmark'] = 'Imagen de Agua';
$string['printwmark_help'] = 'Una archivo de imagen de agua puede ser ubicado en el fonde de un certificado. Una imagen de agua es un grafico transparente.';
$string['receivedcerts'] = 'Certificados recibidos';
$string['receiveddate'] = 'Fache de recepción';
$string['removecert'] = 'Certificados emitidos borrados';
$string['report'] = 'Reporte';
$string['reportcert'] = 'Reporte de Certificados';
$string['reportcert_help'] = 'Si eliges que SI, entonces la fecha de recepción, código y el nombre del curso serán mostrados en el reporte de certificados del usuario. Si eliges mostrar las calificaciones en este certificado, entonces la calificación también se mostrará en el reporte.';
$string['reviewcertificate'] = 'Revisar tu certificado';
$string['savecert'] = 'Guardar Certificados';
$string['savecert_help'] = 'SI eliges esta opción, entonces una copia de cada certificado de cada usuario será guardado en los archivos de curso dentro de la carpeta moddata para ese certificado. Un link le será mostrado a cada usuario en el reporte de certificados.';
$string['sigline'] = 'linea';
$string['statement'] = 'ha completado el curso';
$string['summaryofattempts'] = 'Resumen de Certificados Recibidos Previamente';
$string['textoptions'] = 'Opciones de Texto';
$string['title'] = 'CERTIFICADO de LOGROS ALCANZADOS';
$string['to'] = 'Otorgado a';
$string['typeA4_embedded'] = 'A4 Embebido';
$string['typeA4_non_embedded'] = 'A4 No-Embebido';
$string['typeletter_embedded'] = 'Letter Embebido';
$string['typeletter_non_embedded'] = 'Letter No-Embebido';
$string['userdateformat'] = 'Lenguaje de Fecha del Usuario';
$string['validate'] = 'Verificar';
$string['verifycertificate'] = 'Verificar Certificado';
$string['viewcertificateviews'] = 'Ver {$a} certificados emitidos';
$string['viewed'] = 'Has recibido este certificado el:';
$string['viewtranscript'] = 'Ver certificados';