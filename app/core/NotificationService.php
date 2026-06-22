<?php
/**
 * app/core/NotificationService.php
 *
 * High-level helper that controllers call to notify a user.
 * Creates an in-app notification row AND sends an email (using org's
 * SMTP override if configured, otherwise the platform default).
 */

class NotificationService
{
    private const QR_CID = 'gatepass-qr';

    public static function notify(int $userId, ?int $orgId, string $type, string $title, string $message, ?int $visitId = null, ?string $emailHtml = null, array $inlineImages = []): void
    {
        $notificationId = Notification::create([
            'org_id'   => $orgId,
            'user_id'  => $userId,
            'visit_id' => $visitId,
            'type'     => $type,
            'title'    => $title,
            'message'  => $message,
            'channel'  => 'both',
        ]);

        $user = User::find($userId);
        if (!$user || empty($user['email'])) {
            return;
        }

        $overrides = $orgId ? self::orgSmtpOverrides($orgId) : [];
        $body = $emailHtml ?? self::defaultTemplate($title, $message);

        $sent = Mailer::send($user['email'], $title, $body, $overrides, $inlineImages);
        Notification::markEmailStatus((int)$notificationId, $sent ? 'sent' : 'failed');
    }

    /**
     * Send a gate-pass email to any address (visitor may not have an account).
     */
    public static function emailGatePass(string $toEmail, int $orgId, string $subject, array $visit, string $heading, string $intro, array $detailLines = []): bool
    {
        $inlineImages = self::qrInlineImages($visit);
        $html = self::gatePassEmailHtml($visit, $heading, $intro, $detailLines, !empty($inlineImages));
        $overrides = self::orgSmtpOverrides($orgId);
        return Mailer::send($toEmail, $subject, $html, $overrides, $inlineImages);
    }

    private static function orgSmtpOverrides(int $orgId): array
    {
        $settings = DB::one("SELECT * FROM org_settings WHERE org_id = ?", [$orgId]);
        if (!$settings || empty($settings['smtp_host'])) {
            return [];
        }
        return $settings;
    }

    private static function defaultTemplate(string $title, string $message): string
    {
        $appName = APP_NAME;
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        return <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 560px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <h2 style="color:#0f172a;">{$safeTitle}</h2>
            <p style="color:#334155; font-size:15px; line-height:1.6;">{$safeMessage}</p>
            <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
            <p style="color:#94a3b8; font-size:12px;">This is an automated message from {$appName}. Please do not reply to this email.</p>
        </div>
        HTML;
    }

    /** @return array<string, string> CID => absolute file path */
    private static function qrInlineImages(array $visit): array
    {
        if (empty($visit['qr_image_path'])) {
            return [];
        }
        $path = QrCodeGenerator::absolutePath($visit['qr_image_path']);
        return is_readable($path) ? [self::QR_CID => $path] : [];
    }

    public static function gatePassEmailHtml(array $visit, string $heading, string $intro, array $detailLines = [], bool $hasQrImage = true): string
    {
        $safeHeading = htmlspecialchars($heading, ENT_QUOTES, 'UTF-8');
        $safeIntro = htmlspecialchars($intro, ENT_QUOTES, 'UTF-8');

        if ($hasQrImage) {
            $qrBlock = '<img src="cid:' . self::QR_CID . '" alt="Gate Pass QR Code" style="width:200px;height:200px;display:block;margin:0 auto;">';
        } elseif (!empty($visit['qr_code'])) {
            $token = htmlspecialchars($visit['qr_code'], ENT_QUOTES, 'UTF-8');
            $qrBlock = '<p style="color:#334155;font-size:14px;text-align:center;"><strong>Gate pass token:</strong><br><span style="font-family:monospace;font-size:16px;letter-spacing:0.05em;">' . $token . '</span></p>';
        } else {
            $qrBlock = '<p style="color:#b45309;font-size:14px;text-align:center;">QR code unavailable — contact your host.</p>';
        }

        $details = '';
        foreach ($detailLines as $line) {
            $details .= '<p style="color:#334155;font-size:14px;margin:6px 0;">' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</p>';
        }

        if ($details === '' && !empty($visit['visitor_name'])) {
            $location = trim(($visit['building_name'] ?? '') . ($visit['room_name'] ? ' - ' . $visit['room_name'] : ''));
            $details = '<p style="color:#334155;font-size:14px;">'
                . '<strong>Visitor:</strong> ' . htmlspecialchars($visit['visitor_name'], ENT_QUOTES, 'UTF-8') . '<br>'
                . '<strong>Host:</strong> ' . htmlspecialchars($visit['host_name'] ?? '—', ENT_QUOTES, 'UTF-8') . '<br>'
                . '<strong>Location:</strong> ' . htmlspecialchars($location ?: '—', ENT_QUOTES, 'UTF-8')
                . '</p>';
        }

        return <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 560px; margin:0 auto; padding:24px; border:1px solid #e5e7eb; border-radius:8px;">
            <h2 style="color:#0f172a;">{$safeHeading}</h2>
            <p style="color:#334155; font-size:15px; line-height:1.6;">{$safeIntro}</p>
            <div style="text-align:center; margin: 24px 0;">{$qrBlock}</div>
            {$details}
            <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
            <p style="color:#94a3b8; font-size:12px;">This is an automated message from VisitSecure KE.</p>
        </div>
        HTML;
    }

    /** Email + in-app notification specifically for a new gate pass with embedded QR image */
    public static function sendGatePass(array $visit, array $recipient): void
    {
        $title = 'Your Gate Pass for ' . ($visit['building_name'] ?? 'your visit');
        $message = 'Your visit has been scheduled. Present the QR code below at the gate for entry.';
        $inlineImages = self::qrInlineImages($visit);
        $html = self::gatePassEmailHtml(
            $visit,
            $title,
            $message,
            [],
            !empty($inlineImages)
        );

        self::notify((int)$recipient['id'], $visit['org_id'], 'gate_pass', $title, $message, (int)$visit['id'], $html, $inlineImages);
    }
}
