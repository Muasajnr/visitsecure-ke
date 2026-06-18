<?php
/**
 * app/core/Mailer.php
 *
 * Minimal native SMTP client (no Composer/PHPMailer dependency).
 * Supports STARTTLS, AUTH LOGIN - works with Gmail SMTP + App Passwords.
 *
 * Usage:
 *   Mailer::send('to@example.com', 'Subject', '<p>HTML body</p>');
 *   Mailer::send('to@example.com', 'Subject', '<p>HTML body</p>', $orgSmtpOverrides);
 */

class Mailer
{
    private $socket;
    private array $config;

    public function __construct(array $overrides = [])
    {
        $this->config = [
            'host'     => $overrides['smtp_host'] ?? MAIL_HOST,
            'port'     => $overrides['smtp_port'] ?? MAIL_PORT,
            'username' => $overrides['smtp_username'] ?? MAIL_USERNAME,
            'password' => $overrides['smtp_app_password'] ?? MAIL_PASSWORD,
            'from_email' => $overrides['smtp_from_email'] ?? MAIL_FROM_EMAIL,
            'from_name'  => $overrides['smtp_from_name'] ?? MAIL_FROM_NAME,
        ];
    }

    /**
     * Static convenience method. Returns true on success, false on failure.
     * Never throws - logs failures to storage/logs/mail.log instead, so a
     * mail outage never breaks the booking/check-in flow.
     */
    public static function send(string $toEmail, string $subject, string $htmlBody, array $overrides = []): bool
    {
        try {
            $mailer = new self($overrides);
            return $mailer->dispatch($toEmail, $subject, $htmlBody);
        } catch (Throwable $e) {
            self::log('ERROR sending to ' . $toEmail . ': ' . $e->getMessage());
            return false;
        }
    }

    private function dispatch(string $toEmail, string $subject, string $htmlBody): bool
    {
        $c = $this->config;

        $this->socket = @fsockopen($c['host'], $c['port'], $errno, $errstr, 15);
        if (!$this->socket) {
            self::log("Connection failed: {$errstr} ({$errno})");
            return false;
        }

        $this->read();
        $this->write("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $this->read();

        $this->write("STARTTLS");
        $this->read();

        if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            self::log("TLS negotiation failed");
            fclose($this->socket);
            return false;
        }

        $this->write("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $this->read();

        $this->write("AUTH LOGIN");
        $this->read();
        $this->write(base64_encode($c['username']));
        $this->read();
        $this->write(base64_encode($c['password']));
        $authResponse = $this->read();

        if (strpos($authResponse, '235') === false) {
            self::log("Authentication failed for {$c['username']}: {$authResponse}");
            fclose($this->socket);
            return false;
        }

        $this->write("MAIL FROM: <{$c['from_email']}>");
        $this->read();
        $this->write("RCPT TO: <{$toEmail}>");
        $this->read();
        $this->write("DATA");
        $this->read();

        $boundary = md5(uniqid((string) time()));
        $headers = [];
        $headers[] = "From: {$c['from_name']} <{$c['from_email']}>";
        $headers[] = "To: <{$toEmail}>";
        $headers[] = "Subject: " . $this->encodeSubject($subject);
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "Date: " . date('r');

        $message = implode("\r\n", $headers) . "\r\n\r\n" . $htmlBody . "\r\n.";
        $this->write($message);
        $sendResponse = $this->read();

        $this->write("QUIT");
        fclose($this->socket);

        return strpos($sendResponse, '250') !== false;
    }

    private function write(string $data): void
    {
        fwrite($this->socket, $data . "\r\n");
    }

    private function read(): string
    {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break; // last line of multiline response
        }
        return $response;
    }

    private function encodeSubject(string $subject): string
    {
        return '=?UTF-8?B?' . base64_encode($subject) . '?=';
    }

    private static function log(string $message): void
    {
        $logDir = STORAGE_PATH . '/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0775, true);
        file_put_contents($logDir . '/mail.log', '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}
