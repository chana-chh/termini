<?php

namespace App\Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private static $instance = null;

    public static function instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        static::$config = Config::get('mail');
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    public static function sendMail(array $to, $subject, $body, $html = true, $from = null, $from_name = null)
    {
        $mail = new PHPMailer(true);

        $config = Config::get('mail');

        try {
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->Port = $config['port'];
            if ($config['port'] === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($config['port'] === 587) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $from = $from !== null ? : $config['from'];
            $from_name = $from_name !== null ? : $config['from_name'];

            $mail->setFrom($from, $from_name);

            foreach ($to as $t) {
                $mail->addAddress($t['email'], $t['name']);
            }

            $mail->Subject = $subject;

            if ($html) {
                $mail->isHTML(true);
                $mail->Body = $body;
                $mail->AltBody = "Molimo vas da koristite mail klijent koji podržava HTML";
            } else {
                $mail->isHTML(false);
                $mail->Body = $body;
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
