<?php
/** views/orgadmin/dashboard.php */
$hour = (int)date('H');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
?>
<div class="mb-6 flex items-start justify-between flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= $greeting ?><?= $org ? ', ' . e($org['name']) : '' ?></h1>
        <p class="text-sm text-ink/50 mt-1">Here's what's been happening<?= $visitStats['totalToday'] > 0 ? ' — ' . $visitStats['totalToday'] . ' visit' . ($visitStats['totalToday'] === 1 ? '' : 's') . ' so far today' : ' today' ?>.</p>
    </div>
    <?php if ($org): ?>
        <span class="px-3 py-1.5 rounded-full text-xs font-semibold <?= $org['subscription_status'] === 'active' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-yellow-50 text-yellow-700 border border-yellow-100' ?>">
            <?= e(ucfirst($org['subscription_plan'])) ?> plan &middot; <?= e(ucfirst($org['subscription_status'])) ?>
        </span>
    <?php endif; ?>
</div>

<!-- Stat cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">Today's visits</p>
        <p class="font-display text-3xl font-semibold mt-1.5"><?= $visitStats['totalToday'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">In building now</p>
        <p class="font-display text-3xl font-semibold mt-1.5 text-green-700"><?= $visitStats['checkedIn'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">Awaiting approval</p>
        <p class="font-display text-3xl font-semibold mt-1.5 text-yellow-600"><?= $visitStats['pending'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">This month</p>
        <p class="font-display text-3xl font-semibold mt-1.5"><?= $visitStats['thisMonth'] ?></p>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4 mb-8">
    <a href="<?= url('/orgadmin/buildings') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-blue-200 hover:shadow-sm transition group">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">Buildings</p>
        <p class="font-display text-2xl font-semibold mt-1.5"><?= $counts['buildings'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block group-hover:underline">Manage &rarr;</span>
    </a>
    <a href="<?= url('/orgadmin/users') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-blue-200 hover:shadow-sm transition group">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">Staff &amp; users</p>
        <p class="font-display text-2xl font-semibold mt-1.5"><?= $counts['users'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block group-hover:underline">Manage &rarr;</span>
    </a>
    <a href="<?= url('/orgadmin/events') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-blue-200 hover:shadow-sm transition group">
        <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold">Events</p>
        <p class="font-display text-2xl font-semibold mt-1.5"><?= $counts['events'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block group-hover:underline">View all &rarr;</span>
    </a>
</div>

<!-- Recent visits -->
<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Recent visits</h2>
        <a href="<?= url('/orgadmin/visits') ?>" class="text-sm text-brick font-semibold hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/40 text-xs uppercase tracking-wide bg-ink/[0.02]">
                    <th class="px-5 py-3 font-semibold">Visitor</th>
                    <th class="px-5 py-3 font-semibold">Host</th>
                    <th class="px-5 py-3 font-semibold">Location</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold">When</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                <?php if (empty($recentVisits)): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-ink/35 text-sm">
                            No visits recorded yet.<br>
                            <span class="text-xs mt-1 inline-block">Visits will appear here once your gateman starts checking people in.</span>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($recentVisits as $v): ?>
                <tr>
                    <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                    <td class="px-5 py-3 text-ink/55"><?= e($v['host_name'] ?? '—') ?></td>
                    <td class="px-5 py-3 text-ink/55"><?= e($v['building_name'] ?? '—') ?><?= $v['room_name'] ? ' &middot; ' . e($v['room_name']) : '' ?></td>
                    <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                    <td class="px-5 py-3 text-ink/40"><?= formatDate($v['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
