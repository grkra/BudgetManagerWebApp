<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Config;

/**
 * Mail
 */
class Mail
{

    /**
     * Send a message

     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        $mail = new PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host = Config::MAIL_HOST;
            $mail->Port = Config::MAIL_PORT;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->Username = Config::MAIL_USERNAME;
            $mail->Password = Config::MAIL_PASSWORD;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom(Config::MAIL_FROM_ADDRESS, Config::MAIL_FROM_NAME);
            $mail->addAddress($to);
            $mail->addReplyTo(Config::MAIL_FROM_ADDRESS, Config::MAIL_FROM_NAME);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->msgHTML($html);
            $mail->AltBody = $text;
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}