<?php
namespace App\Services;

use App\Core\Logger;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService {
    private PHPMailer $mailer;

    public function __construct(string $account = 'noreply') {
        $accounts = MAIL_ACCOUNTS;

        if (!isset($accounts[$account])) {
            throw new \RuntimeException('Compte mail introuvable : ' . $account);
        }

        $config = $accounts[$account];

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = MAIL_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = MAIL_PORT;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom($config['from'], $config['name']);

        if (APP_DEBUG) {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }
    }

    private function send(string $to, string $toName, string $subject, string $template, array $vars = []): bool {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $toName);
            $this->mailer->Subject = $subject;
            $this->mailer->isHTML(true);
            $this->mailer->Body = $this->render($template, $vars);
            $this->mailer->AltBody = strip_tags($this->mailer->Body);
            $this->mailer->send();

            Logger::info('Email envoyé', ['to' => $to, 'subject' => $subject, 'template' => $template]);

            return true;
        } catch (Exception $e) {
            Logger::error('Erreur envoi email', ['to' => $to, 'subject' => $subject, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public static function welcome(string $to, string $username): bool {
        return (new self('noreply'))->send(
            to: $to,
            toName: $username,
            subject: 'Bienvenue sur ' . APP_NAME,
            template: 'welcome',
            vars: [
                'username' => $username,
                'app_url' => APP_URL,
                'app_name' => APP_NAME,
            ]
        );
    }

    public static function passwordReset(string $to, string $username, string $token): bool {
        return (new self('noreply'))->send(
            to:       $to,
            toName:   $username,
            subject:  'Réinitialisation de votre mot de passe',
            template: 'password_reset',
            vars: [
                'username'  => $username,
                'reset_url' => APP_URL . '/reinitialiser-mot-de-passe?token=' . $token,
                'app_name'  => APP_NAME,
                'expires'   => '1 heure',
            ]
        );
    }

    public static function orderConfirmation(string $to, string $username, array $order): bool {
        return (new self('noreply'))->send(
            to:       $to,
            toName:   $username,
            subject:  'Confirmation de votre commande #' . $order['id'],
            template: 'order_confirmation',
            vars: [
                'username' => $username,
                'order'    => $order,
                'app_url'  => APP_URL,
                'app_name' => APP_NAME,
            ]
        );
    }

    public static function invoice(string $to, string $username, array $invoice, string $pdfPath): bool {
        $mail = new self('invoice');

        try {
            $mail->mailer->clearAddresses();
            $mail->mailer->clearAttachments();
            $mail->mailer->addAddress($to, $username);
            $mail->mailer->Subject = 'Votre facture #' . $invoice['number'];
            $mail->mailer->isHTML(true);
            $mail->mailer->Body    = $mail->render('invoice', [
                'username' => $username,
                'invoice'  => $invoice,
                'app_name' => APP_NAME,
            ]);
            $mail->mailer->AltBody = strip_tags($mail->mailer->Body);
            $mail->mailer->addAttachment($pdfPath, 'facture-' . $invoice['number'] . '.pdf');
            $mail->mailer->send();

            Logger::info('Facture envoyée', [
                'to'      => $to,
                'invoice' => $invoice['number'],
            ]);

            return true;

        } catch (Exception $e) {
            Logger::error('Erreur envoi facture', [
                'to'    => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function render(string $template, array $vars = []): string {
        $file = __DIR__ . '/../../VIEWS/EMAILS/' . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException('Template email introuvable : ' . $template);
        }

        extract($vars);
        ob_start();
        require $file;
        return ob_get_clean();
    }
}