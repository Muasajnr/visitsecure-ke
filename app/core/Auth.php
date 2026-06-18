<?php
/**
 * app/core/Auth.php
 * Handles login session state and role/org access checks.
 * Multi-tenancy enforcement: Auth::orgId() is used by every model/query
 * that touches tenant-scoped tables, so an org can never see another org's data.
 */

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['org_id']    = $user['org_id'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['room_id']   = $user['room_id'] ?? null;
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        self::start();
        if (!self::check()) return null;
        return [
            'id'        => $_SESSION['user_id'],
            'org_id'    => $_SESSION['org_id'],
            'role'      => $_SESSION['role'],
            'full_name' => $_SESSION['full_name'],
            'email'     => $_SESSION['email'],
            'room_id'   => $_SESSION['room_id'],
        ];
    }

    public static function id(): ?int
    {
        return self::check() ? (int)$_SESSION['user_id'] : null;
    }

    public static function orgId(): ?int
    {
        return self::check() ? ($_SESSION['org_id'] !== null ? (int)$_SESSION['org_id'] : null) : null;
    }

    public static function role(): ?string
    {
        return self::check() ? $_SESSION['role'] : null;
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function hasRole(array $roles): bool
    {
        return in_array(self::role(), $roles, true);
    }

    /** Redirect to login if not authenticated */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/login');
        }
    }

    /** Redirect to dashboard if role not permitted */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            require VIEWS_PATH . '/errors/403.php';
            exit;
        }
    }

    /** Returns the correct dashboard URL for the logged-in user's role */
    public static function dashboardUrl(): string
    {
        return match (self::role()) {
            'super_admin'   => '/superadmin/dashboard',
            'org_admin'     => '/orgadmin/dashboard',
            'gateman'       => '/gateman/dashboard',
            'host'          => '/host/dashboard',
            'event_manager' => '/events/dashboard',
            'visitor'       => '/visitor/dashboard',
            default         => '/login',
        };
    }
}
