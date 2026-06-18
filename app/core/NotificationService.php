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
    public static function notify(int $userId, ?int $orgId, string $type, string $title, string $message, ?int $visitId = null, ?string $emailHtml = null): void
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

        $sent = Mailer::send($user['email'], $title, $body, $overrides);
        Notification::markEmailStatus((int)$notificationId, $sent ? 'sent' : 'failed');
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
        return <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 560px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <h2 style="color:#0f172a;">{$title}</h2>
            <p style="color:#334155; font-size:15px; line-height:1.6;">{$message}</p>
            <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
            <p style="color:#94a3b8; font-size:12px;">This is an automated message from {$appName}. Please do not reply to this email.</p>
        </div>
        HTML;
    }

    /** Email + in-app notification specifically for a new gate pass with QR attached as a link */
    public static function sendGatePass(array $visit, array $recipient): void
    {
        $qrUrl = QrCode::publicUrl($visit['qr_image_path']);
        $title = "Your Gate Pass for " . ($visit['building_name'] ?? 'your visit');
        $message = "Your visit has been scheduled. Present the QR code below at the gate for entry.";

        $html = <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 560px; margin:0 auto; padding:24px; border:1px solid #e5e7eb; border-radius:8px;">
            <h2 style="color:#0f172a;">{$title}</h2>
            <p style="color:#334155; font-size:15px; line-height:1.6;">{$message}</p>
            <div style="text-align:center; margin: 24px 0;">
                <img src="{$qrUrl}" alt="Gate Pass QR Code" style="width:200px;height:200px;">
            </div>
            <p style="color:#334155; font-size:14px;"><strong>Visitor:</strong> {$visit['visitor_name']}<br>
            <strong>Host:</strong> {$visit['host_name']}<br>
            <strong>Location:</strong> {$visit['building_name']} - {$visit['room_name']}</p>
            <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
            <p style="color:#94a3b8; font-size:12px;">This is an automated message from VisitSecure KE.</p>
        </div>
        HTML;

        self::notify((int)$recipient['id'], $visit['org_id'], 'gate_pass', $title, $message, (int)$visit['id'], $html);
    }
}
