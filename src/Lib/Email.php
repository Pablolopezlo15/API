<?php

namespace Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    public $email;

    public $token;

    public function __construct($email, $token) {
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        try{
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                            
            $mail->Host       = $_ENV['MAIL_HOST']; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                 
            $mail->SMTPAuth   = true;                                  
            $mail->Username   = $_ENV['MAIL_USERNAME'];                  
            $mail->Password   = $_ENV['MAIL_PASSWORD'];                             
            $mail->Port       = $_ENV['MAIL_PORT'];
    
            //Recipients
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($this->email, 'Usuario');
    
            // Content
            $mail->isHTML(true);                                 
            $mail->Subject = 'Confirmación de cuenta';
            $mail->Body = 'Para confirmar tu cuenta, haz click en el siguiente enlace: <a href="http://localhost/API/usuario/ConfirmarCuenta/' . $this->token . '">Confirmar cuenta</a>';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return 'Error al enviar el correo: ' . $e->getMessage();
        }
    }


}