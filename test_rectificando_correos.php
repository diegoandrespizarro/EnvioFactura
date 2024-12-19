<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\EnvioFacturaPDF\vendor\autoload.php';
$file = new SplFileObject("./csv/sh_envios.csv");

while (!$file->eof()) {
    $linea = trim($file->fgets());
    $campos_mail = explode(";", $linea);

    // Verificar que la línea tenga el número correcto de campos
    if (count($campos_mail) < 7) {
        continue; // Saltar líneas mal formateadas
    }

    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        // Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.office365.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'facturadigital@baradero.gob.ar'; // SMTP username
        $mail->Password = '2018Facba'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable SSL encryption, TLS also accepted with port 587
        $mail->Port = 587; // TCP port to connect to

        $mail->setFrom('facturadigital@baradero.gob.ar', 'Municipalidad de Baradero');
        $mail->addAddress($campos_mail[5], $campos_mail[4]); // Add a recipient
        $mail->addAttachment('C:\wamp64\www\EnvioFacturaPDF\pdf\1.pdf'); // Add attachments
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Factura Digital - Municipalidad de Baradero';
        $mail->Body = 'Estimado/a, le enviamos adjunta la factura de ' . $campos_mail[6] . ', cuota ' . $campos_mail[1] . ' de la cuenta ' . $campos_mail[2] . '. Por favor, descartar el correo electrónico previo si es que les ha llegado y tomar éste como único válido. Solicitamos disculpas por el error de confección y demora. Saludos Cordiales.-';

        $mail->send();
        echo 'ME_CONTAR';
    } catch (Exception $e) {
        echo 'MNE_CONTAR';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
//aca muestro las veces que me dice que envio y las veces que me dice que no envio
echo 'ME_CONTAR: ' . substr_count(ob_get_clean(), 'ME_CONTAR') . PHP_EOL;
echo 'MNE_CONTAR: ' . substr_count(ob_get_clean(), 'MNE_CONTAR') . PHP_EOL;

$file = null; // Cerrar el archivo explícitamente



