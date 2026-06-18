<?php
/**
 * views/partials/sidebar.php
 * Navigation links change based on the logged-in user's role.
 */
$role = Auth::role();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function navLink(string $href, string $label, string $icon, string $currentPath): string
{
    $active = $currentPath === $href || str_starts_with($currentPath, $href . '/');
    $classes = $active
        ? 'bg-white/10 text-white font-semibold'
        : 'text-white/70 hover:bg-white/5 hover:text-white';
    return "<a href=\"" . url($href) . "\" class=\"flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {$classes}\">
        <span class=\"w-5 text-center\">{$icon}</span><span>{$label}</span>
    </a>";
}
?>
<aside class="hidden md:flex md:flex-col w-64 bg-ink shrink-0">
    <div class="px-5 py-5 border-b border-white/10">
        <a href="<?= url('/') ?>" class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-md bg-brick flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
            <span class="text-white font-display font-semibold text-lg">VisitSecure</span>
        </a>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <?php if ($role === 'super_admin'): ?>
            <?= navLink('/superadmin/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/superadmin/organizations', 'Organisations', '&#127970;', $path) ?>
            <?= navLink('/superadmin/users', 'All Users', '&#128101;', $path) ?>
            <?= navLink('/superadmin/reports', 'Reports', '&#128202;', $path) ?>

        <?php elseif ($role === 'org_admin'): ?>
            <?= navLink('/orgadmin/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/orgadmin/buildings', 'Buildings & Floors', '&#127970;', $path) ?>
            <?= navLink('/orgadmin/users', 'Staff & Users', '&#128101;', $path) ?>
            <?= navLink('/orgadmin/visits', 'All Visits', '&#128203;', $path) ?>
            <?= navLink('/orgadmin/events', 'Events', '&#127881;', $path) ?>
            <?= navLink('/orgadmin/reports', 'Analytics', '&#128202;', $path) ?>
            <?= navLink('/orgadmin/settings', 'Settings', '&#9881;', $path) ?>

        <?php elseif ($role === 'gateman'): ?>
            <?= navLink('/gateman/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/gateman/scan', 'Scan QR Code', '&#128247;', $path) ?>
            <?= navLink('/gateman/walkin', 'Register Walk-in', '&#128221;', $path) ?>
            <?= navLink('/gateman/log', 'Gate Log', '&#128203;', $path) ?>

        <?php elseif ($role === 'host'): ?>
            <?= navLink('/host/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/host/invite', 'Invite Visitor', '&#9993;', $path) ?>
            <?= navLink('/host/visits', 'My Visitors', '&#128101;', $path) ?>

        <?php elseif ($role === 'event_manager'): ?>
            <?= navLink('/events/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/events/create', 'Schedule Event', '&#127881;', $path) ?>

        <?php elseif ($role === 'visitor'): ?>
            <?= navLink('/visitor/dashboard', 'Dashboard', '&#9737;', $path) ?>
            <?= navLink('/visitor/book', 'Book a Visit', '&#128197;', $path) ?>
            <?= navLink('/visitor/visits', 'My Visits & Passes', '&#127915;', $path) ?>
        <?php endif; ?>

        <div class="pt-3 mt-3 border-t border-white/10">
            <?= navLink('/notifications', 'Notifications', '&#128276;', $path) ?>
            <?= navLink('/profile', 'My Profile', '&#128100;', $path) ?>
        </div>
    </nav>

    <div class="px-3 py-4 border-t border-white/10">
        <a href="<?= url('/logout') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white/70 hover:bg-brick/20 hover:text-white transition">
            <span class="w-5 text-center">&#10140;</span><span>Sign out</span>
        </a>
    </div>
</aside>
