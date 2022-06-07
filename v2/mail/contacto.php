<?php
/**
 * @version 1.0
 */

require("class.phpmailer.php");
require("class.smtp.php");

// Valores enviados desde el formulario
if ( !isset($_POST["nombre"]) || !isset($_POST["email"]) || !isset($_POST["mensaje"]) || !isset($_POST["phone"]) ) {
    die ("Es necesario completar todos los datos del formulario");
}
$nombre = $_POST["nombre"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];
$phone = $_POST["phone"];

// Datos de la cuenta de correo utilizada para enviar vía SMTP
$smtpHost = "ferozo.email";  // Dominio alternativo brindado en el email de alta 
$smtpUsuario = "tomasbursztyn@eneconsultora.com";  // Mi cuenta de correo
$smtpClave = "Hosting@53";  // Mi contraseña

// Email donde se enviaran los datos cargados en el formulario de contacto
$emailDestino = "tomasbursztyn.tb@gmail.com";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Port = 465; 
$mail->SMTPSecure = 'ssl';
$mail->IsHTML(true); 
$mail->CharSet = "utf-8";


// VALORES A MODIFICAR //
$mail->Host = $smtpHost; 
$mail->Username = $smtpUsuario; 
$mail->Password = $smtpClave;

$mail->From = $email; // Email desde donde envío el correo.
$mail->FromName = $nombre;
$mail->AddAddress($emailDestino); // Esta es la dirección a donde enviamos los datos del formulario

$mail->Subject = "Contacto desde el website:  $nombre"; // Este es el titulo del email.
$mensajeHtml = nl2br($mensaje);
$mail->Body = "Haz recibido un mensaje desde el formulario de contacto de su website.\n\n"."Estos son los detalles:\n\nNombre: $nombre\n\nEmail: $email\n\nTeléfono: $phone\n\nMensaje:\n$mensaje"; // Texto del email en formato HTML
// FIN - VALORES A MODIFICAR //

$estadoEnvio = $mail->Send(); 
if($estadoEnvio){
    echo "El correo fue enviado correctamente.";
} else {
    echo "Ocurrió un error inesperado.";
}
