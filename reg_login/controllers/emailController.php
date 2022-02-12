<?php

require_once 'vendor/autoload.php';
require_once 'config/constants.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.org', 465, 'ssl'))
  ->setUsername(EMAIL)
  ->setPassword(PASSWORD);

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($userEmail, $token){
    global $mailer;

    $body = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Verify email</title>
            </head>
            <body>
                <p>
                    Thank you for signing up on our website.
                    Please click on the following link below to verify your email.
                </p>
                <a href="http://localhost/zadatak/index.php?token'.$token.'">Verify your email address.</a>
            </body>
            </html>';

    // Create a message
    $message = (new Swift_Message('Verify your email address...'))
    ->setFrom(EMAIL)
    ->setTo($userEmail)
    ->setBody($body, 'text/html');
    ;

    // Send the message
    $result = $mailer->send($message);
}

function sendPassword(){

}


?>