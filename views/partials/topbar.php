<?php
/**
 * views/partials/topbar.php
 */
$user    = Auth::user();
$orgName = null;
if (!empty($user['org_id'])) {
    $org = Organization::find($user['org_id']);
    $orgName = $org['name'] ?? null;
}
$unread = $user ? Notification::unreadCount($user['id']) : 0;
$initial = strtoupper(substr($user['full_name'] ?? '?', 0, 1));
?>
<header class="bg-white border-b border-ink/10 px-5 py-3.5 flex items-center justify-between sticky top-0 z-20">
    <!-- Mobile hamburger + page title -->
    <div class="flex items-center gap-3 min-w-0">
        <button
            class="md:hidden p-1.5 rounded-md hover:bg-ink/5 text-ink/70 transition"
            onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
            aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="min-w-0">
            <h1 class="font-display font-semibold text-sm md:text-base leading-tight truncate"><?= e($pageTitle ?? 'Dashboard') ?></h1>
            <?php if ($orgName): ?>
                <p class="text-[11px] text-ink/40 truncate leading-tight"><?= e($orgName) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right side actions -->
    <div class="flex items-center gap-3">
        <!-- Notification bell -->
        <a href="<?= url('/notifications') ?>" class="relative p-1.5 rounded-md hover:bg-ink/5 text-ink/60 hover:text-ink transition" aria-label="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <?php if ($unread > 0): ?>
                <span class="absolute top-0 right-0 w-4 h-4 bg-brick text-white text-[9px] font-bold rounded-full flex items-center justify-center"><?= min($unread, 9) ?></span>
            <?php endif; ?>
        </a>

        <!-- Avatar + name -->
        <a href="<?= url('/profile') ?>" class="flex items-center gap-2 hover:opacity-75 transition">
            <span class="w-7 h-7 rounded-full bg-ink text-white text-xs font-semibold font-display flex items-center justify-center"><?= $initial ?></span>
            <span class="hidden md:inline text-sm font-medium text-ink/80 max-w-[120px] truncate"><?= e($user['full_name'] ?? '') ?></span>
        </a>
    </div>
</header>

<!-- Mobile slide-down nav -->
<div id="mobileMenu" class="hidden md:hidden bg-ink text-white border-b border-white/10">
    <div class="px-4 py-3 space-y-0.5">
        <?php
        $role = Auth::role();
        $links = match ($role) {
            'super_admin'   => ['/superadmin/dashboard' => 'Dashboard', '/superadmin/organizations' => 'Organisations', '/superadmin/users' => 'All Users'],
            'org_admin'     => ['/orgadmin/dashboard' => 'Dashboard', '/orgadmin/buildings' => 'Buildings', '/orgadmin/users' => 'Staff', '/orgadmin/visits' => 'Visits', '/orgadmin/events' => 'Events'],
            'gateman'       => ['/gateman/dashboard' => 'Dashboard', '/gateman/scan' => 'Scan QR', '/gateman/walkin' => 'Register Walk-in', '/gateman/log' => 'Gate Log'],
            'host'          => ['/host/dashboard' => 'Dashboard', '/host/invite' => 'Invite Visitor', '/host/visits' => 'My Visitors'],
            'event_manager' => ['/events/dashboard' => 'Dashboard', '/events/create' => 'Schedule Event'],
            'visitor'       => ['/visitor/dashboard' => 'Dashboard', '/visitor/book' => 'Book a Visit', '/visitor/visits' => 'My Passes'],
            default         => [],
        };
        foreach ($links as $href => $label): ?>
            <a href="<?= url($href) ?>" class="block py-2 px-2 text-sm text-white/70 hover:text-white rounded-md hover:bg-white/6 transition"><?= e($label) ?></a>
        <?php endforeach; ?>
        <div class="pt-2 mt-2 border-t border-white/10">
            <a href="<?= url('/notifications') ?>" class="block py-2 px-2 text-sm text-white/70 hover:text-white">Notifications<?= $unread > 0 ? " <span class=\"text-brick font-bold\">($unread)</span>" : '' ?></a>
            <a href="<?= url('/profile') ?>" class="block py-2 px-2 text-sm text-white/70 hover:text-white">My Profile</a>
            <a href="<?= url('/logout') ?>" class="block py-2 px-2 text-sm text-brick font-semibold hover:text-red-400">Sign out</a>
        </div>
    </div>
</div>
