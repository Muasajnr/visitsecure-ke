<?php
/**
 * views/partials/sidebar.php
 */
$role = Auth::role();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function navLink(string $href, string $label, string $iconName, string $currentPath, string $badge = ''): string
{
    $active = $currentPath === $href || str_starts_with($currentPath, $href . '/');
    $base   = 'flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all';
    $style  = $active
        ? 'bg-white/12 text-white font-semibold'
        : 'text-white/60 hover:bg-white/6 hover:text-white/90';
    $badgeHtml = $badge
        ? "<span class=\"ml-auto bg-brick text-white text-[10px] rounded-full px-1.5 py-0.5 font-semibold\">{$badge}</span>"
        : '';
    $iconHtml = icon($iconName, 'w-5 h-5 shrink-0');
    return "<a href=\"" . url($href) . "\" class=\"{$base} {$style}\">
        {$iconHtml}
        <span class=\"truncate\">{$label}</span>{$badgeHtml}
    </a>";
}
?>
<aside class="hidden md:flex md:flex-col w-60 bg-ink shrink-0 border-r border-white/8">
    <!-- Logo -->
    <div class="px-5 py-5 border-b border-white/10">
        <a href="<?= url('/') ?>" class="flex items-center gap-2.5">
            <span class="w-7 h-7 rounded-md bg-brick flex items-center justify-center text-white font-display font-bold text-xs shrink-0">VS</span>
            <span class="text-white font-display font-semibold text-[15px] tracking-tight">VisitSecure <span class="text-white/50">KE</span></span>
        </a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        <?php if ($role === 'super_admin'): ?>
            <?= navLink('/superadmin/dashboard',     'Dashboard',        'dashboard', $path) ?>
            <?= navLink('/superadmin/organizations', 'Organisations',    'building', $path) ?>
            <?= navLink('/superadmin/users',         'All Users',        'users', $path) ?>
            <?= navLink('/superadmin/visitors',      'Visitors',         'users', $path) ?>
            <?= navLink('/superadmin/reports',       'Reports',          'chart-bar', $path) ?>

        <?php elseif ($role === 'org_admin'): ?>
            <?= navLink('/orgadmin/dashboard',  'Dashboard',        'dashboard', $path) ?>
            <?= navLink('/orgadmin/buildings',  'Buildings & Floors','building', $path) ?>
            <?= navLink('/orgadmin/users',      'Staff & Users',    'users', $path) ?>
            <?= navLink('/orgadmin/visits',     'All Visits',       'clipboard', $path) ?>
            <?= navLink('/orgadmin/events',     'Events',           'sparkles', $path) ?>
            <?= navLink('/orgadmin/reports',    'Analytics',        'chart-bar', $path) ?>

            <div class="pt-3 mt-3 border-t border-white/10">
                <?= navLink('/orgadmin/settings', 'Settings', 'cog', $path) ?>
            </div>

        <?php elseif ($role === 'gateman'): ?>
            <?= navLink('/gateman/dashboard',   'Dashboard',        'dashboard', $path) ?>
            <?= navLink('/gateman/scan',        'Scan QR Code',     'camera', $path) ?>
            <?= navLink('/gateman/walkin',      'Register Walk-in', 'pencil', $path) ?>
            <?= navLink('/gateman/log',         'Gate Log',         'clipboard', $path) ?>

        <?php elseif ($role === 'host'): ?>
            <?= navLink('/host/dashboard',  'Dashboard',    'dashboard', $path) ?>
            <?= navLink('/host/invite',     'Invite Visitor','envelope', $path) ?>
            <?= navLink('/host/visits',     'My Visitors',  'users', $path) ?>

        <?php elseif ($role === 'event_manager'): ?>
            <?= navLink('/events/dashboard','Dashboard',    'dashboard', $path) ?>
            <?= navLink('/events/create',   'Schedule Event','sparkles', $path) ?>

        <?php elseif ($role === 'visitor'): ?>
            <?= navLink('/visitor/dashboard','Dashboard',       'dashboard', $path) ?>
            <?= navLink('/visitor/book',     'Book a Visit',    'calendar', $path) ?>
            <?= navLink('/visitor/visits',   'My Passes',       'ticket', $path) ?>
        <?php endif; ?>

        <!-- Common bottom links -->
        <div class="pt-3 mt-3 border-t border-white/10">
            <?= navLink('/notifications', 'Notifications', 'bell', $path) ?>
            <?= navLink('/profile',       'My Profile',    'user', $path) ?>
            <!-- <a href="<?= url('/logout') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm mt-1 text-red-300 hover:bg-red-500/15 hover:text-red-200 transition font-medium">
                <?= icon('logout', 'w-5 h-5 shrink-0') ?>
                <span>Log out</span>
            </a> -->
        </div>
    </nav>

    <!-- User + sign out -->
    <div class="px-3 py-4 border-t border-white/10">
        <?php $user = Auth::user(); ?>
        <div class="flex items-center gap-3 px-3 mb-3">
            <span class="w-7 h-7 rounded-full bg-white/15 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                <?= strtoupper(substr($user['full_name'] ?? '?', 0, 1)) ?>
            </span>
            <div class="min-w-0">
                <p class="text-white text-xs font-semibold truncate"><?= e($user['full_name'] ?? '') ?></p>
                <p class="text-white/40 text-[10px] truncate capitalize"><?= str_replace('_', ' ', $user['role'] ?? '') ?></p>
            </div>
        </div>
        <a href="<?= url('/logout') ?>" class="flex items-center justify-center gap-2 w-full px-3.5 py-2.5 rounded-lg text-sm font-semibold bg-red-500/20 text-red-200 border border-red-400/30 hover:bg-red-500/30 hover:text-white transition">
            <?= icon('logout', 'w-5 h-5 shrink-0') ?>
            <span>Log out</span>
        </a>
    </div>
</aside>
