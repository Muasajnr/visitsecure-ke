<?php
/**
 * app/helpers/functions.php
 * Small reusable helper functions loaded globally.
 */

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = '/'): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function old(string $key, $default = '')
{
    return e($_SESSION['_old'][$key] ?? $default);
}

function setOld(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clearOld(): void
{
    unset($_SESSION['_old']);
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
    if (!empty($_SESSION['_flash'][$type])) {
        $msg = $_SESSION['_flash'][$type];
        unset($_SESSION['_flash'][$type]);
        return $msg;
    }
    return null;
}

function generateUuid(): string
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function generateQrToken(): string
{
    return strtoupper(bin2hex(random_bytes(12))); // 24-char unique gate-pass token
}

/**
 * Render a view file inside the given layout.
 * $view path is relative to /views, no extension, e.g. 'auth/login'
 */
function view(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    $contentFile = VIEWS_PATH . '/' . $view . '.php';

    if (!file_exists($contentFile)) {
        http_response_code(404);
        echo "View not found: {$view}";
        return;
    }

    if ($layout === false || $layout === null) {
        require $contentFile;
        return;
    }

    ob_start();
    require $contentFile;
    $content = ob_get_clean();

    require VIEWS_PATH . '/' . $layout . '.php';
}

function csrfToken(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrfField(): string
{
    return '<input type="hidden" name="_csrf" value="' . csrfToken() . '">';
}

function verifyCsrf(): bool
{
    $token = $_POST['_csrf'] ?? '';
    return !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function input(string $key, $default = null)
{
    $val = $_POST[$key] ?? $_GET[$key] ?? $default;
    return is_string($val) ? trim($val) : $val;
}

function formatDate(?string $datetime, string $format = 'd M Y, h:i A'): string
{
    if (!$datetime) return '-';
    return date($format, strtotime($datetime));
}

function statusBadge(string $status): string
{
    $map = [
        'pending'     => 'bg-yellow-100 text-yellow-800',
        'approved'    => 'bg-blue-100 text-blue-800',
        'rejected'    => 'bg-red-100 text-red-800',
        'checked_in'  => 'bg-green-100 text-green-800',
        'checked_out' => 'bg-gray-100 text-gray-700',
        'expired'     => 'bg-gray-200 text-gray-600',
        'cancelled'   => 'bg-red-100 text-red-700',
        'active'      => 'bg-green-100 text-green-800',
        'trial'       => 'bg-indigo-100 text-indigo-800',
        'suspended'   => 'bg-red-100 text-red-800',
    ];
    $classes = $map[$status] ?? 'bg-gray-100 text-gray-700';
    $label = str_replace('_', ' ', ucwords($status, '_'));
    return "<span class=\"px-2 py-1 rounded-full text-xs font-medium {$classes}\">{$label}</span>";
}

function logAudit(?int $orgId, ?int $userId, string $action, string $description = ''): void
{
    try {
        DB::insert(
            "INSERT INTO audit_logs (org_id, user_id, action, description, ip_address) VALUES (?,?,?,?,?)",
            [$orgId, $userId, $action, $description, $_SERVER['REMOTE_ADDR'] ?? null]
        );
    } catch (Throwable $e) {
        // fail silently, audit logging should never break the app
    }
}
