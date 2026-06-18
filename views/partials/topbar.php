<?php
/**
 * views/partials/topbar.php
 */
$user = Auth::user();
$orgName = null;
if (!empty($user['org_id'])) {
    $org = Organization::find($user['org_id']);
    $orgName = $org['name'] ?? null;
}
$unread = $user ? Notification::unreadCount($user['id']) : 0;
?>
<header class="bg-white border-b border-ink/10 px-4 md:px-6 py-3 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-3 min-w-0">
        <button class="md:hidden text-ink" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">&#9776;</button>
        <div class="min-w-0">
            <p class="font-display font-semibold text-sm md:text-base truncate"><?= e($pageTitle ?? 'Dashboard') ?></p>
            <?php if ($orgName): ?>
                <p class="text-xs text-ink/50 truncate"><?= e($orgName) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <a href="<?= url('/notifications') ?>" class="relative text-ink/70 hover:text-ink">
            <span class="text-xl">&#128276;</span>
            <?php if ($unread > 0): ?>
                <span class="absolute -top-1 -right-1 bg-brick text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center"><?= min($unread, 9) ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= url('/profile') ?>" class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-full bg-ink text-white flex items-center justify-center text-xs font-semibold font-display">
                <?= e(strtoupper(substr($user['full_name'] ?? '?', 0, 1))) ?>
            </span>
            <span class="hidden md:inline text-sm font-medium"><?= e($user['full_name'] ?? '') ?></span>
        </a>
    </div>
</header>

<div id="mobileMenu" class="hidden md:hidden bg-ink text-white px-4 py-3 space-y-2">
    <?php
    $role = Auth::role();
    $links = match ($role) {
        'super_admin'   => ['/superadmin/dashboard' => 'Dashboard', '/superadmin/organizations' => 'Organisations'],
        'org_admin'     => ['/orgadmin/dashboard' => 'Dashboard', '/orgadmin/buildings' => 'Buildings', '/orgadmin/visits' => 'Visits'],
        'gateman'       => ['/gateman/dashboard' => 'Dashboard', '/gateman/scan' => 'Scan QR', '/gateman/walkin' => 'Walk-in'],
        'host'          => ['/host/dashboard' => 'Dashboard', '/host/invite' => 'Invite Visitor', '/host/visits' => 'My Visitors'],
        'event_manager' => ['/events/dashboard' => 'Dashboard', '/events/create' => 'Schedule Event'],
        'visitor'       => ['/visitor/dashboard' => 'Dashboard', '/visitor/book' => 'Book Visit', '/visitor/visits' => 'My Passes'],
        default         => [],
    };
    foreach ($links as $href => $label): ?>
        <a href="<?= url($href) ?>" class="block py-1 text-sm"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="<?= url('/logout') ?>" class="block py-1 text-sm text-brick">Sign out</a>
</div>
