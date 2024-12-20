<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require './vendor/autoload.php'; // Adjust path if necessary

class mail_library
{
    private $mailer;

    public function __construct($config = [])
    {
        $this->mailer         = new PHPMailer(true);
        $config['host']       = 'smtp.gmail.com';
        $config['username']   = 'sonu0896yadav@gmail.com';
        $config['password']   = 'wmcp lscm cqai yzwo';
        $config['port']       = 587;
        $config['encryption'] = 'tls';

        // SMTP configuration
        $this->mailer->isSMTP();
        $this->mailer->Host       = $config['host'] ?? '';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $config['username'] ?? '';
        $this->mailer->Password   = $config['password'] ?? '';
        $this->mailer->SMTPSecure = $config['encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $config['port'] ?? 587;

        // Set sender details
        $this->mailer->setFrom($config['from_email'] ?? 'sonu0896yadav@gmail.com', $config['from_name'] ?? 'Sonu');
    }

    public function sendEmail($to, $subject, $body, $isHTML = true, $attachments = [])
    {
        try {
            // Add recipient(s)
            if (is_array($to)) {
                foreach ($to as $email) {
                    $this->mailer->addAddress($email);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // Add attachments
            foreach ($attachments as $attachment) {
                $this->mailer->addAttachment($attachment);
            }

            // Email content
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            // Send email
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
}