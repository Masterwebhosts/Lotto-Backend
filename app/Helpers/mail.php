<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function send_mail(string $to, string $subject, string $body): bool {
    $mail = new PHPMailer(true);

    try {
        // إعدادات السيرفر
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? 'your_email@example.com';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? 'secret';
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'] ?? 587;

        // المرسل والمستقبل
        $fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@example.com';
        $fromName    = $_ENV['MAIL_FROM_NAME'] ?? 'Lotto Backend';

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($to);

        // المحتوى
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
