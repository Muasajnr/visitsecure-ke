<?php
/** views/orgadmin/dashboard.php */
?>
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Welcome back<?= $org ? ', ' . e($org['name']) : '' ?></h1>
        <p class="text-sm text-ink/55 mt-1">Here's what's happening across your organisation today.</p>
    </div>
    <?php if ($org): ?>
        <span class="px-3 py-1.5 rounded-full text-xs font-medium <?= $org['subscription_status'] === 'active' ? 'bg-verigreen/10 text-verigreen' : 'bg-yellow-100 text-yellow-800' ?>">
            Plan: <?= e(ucfirst($org['subscription_plan'])) ?> &middot; <?= e(ucfirst($org['subscription_status'])) ?>
        </span>
    <?php endif; ?>
</div>

<!-- Stat cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Visits today</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['totalToday'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Currently checked in</p>
        <p class="font-display text-3xl font-semibold mt-1 text-verigreen"><?= $visitStats['checkedIn'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Pending approval</p>
        <p class="font-display text-3xl font-semibold mt-1 text-yellow-600"><?= $visitStats['pending'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">This month</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['thisMonth'] ?></p>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4 mb-8">
    <a href="<?= url('/orgadmin/buildings') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-brick/30 transition">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Buildings</p>
        <p class="font-display text-2xl font-semibold mt-1"><?= $counts['buildings'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block">Manage buildings &rarr;</span>
    </a>
    <a href="<?= url('/orgadmin/users') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-brick/30 transition">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Staff &amp; users</p>
        <p class="font-display text-2xl font-semibold mt-1"><?= $counts['users'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block">Manage staff &rarr;</span>
    </a>
    <a href="<?= url('/orgadmin/events') ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-brick/30 transition">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Events</p>
        <p class="font-display text-2xl font-semibold mt-1"><?= $counts['events'] ?></p>
        <span class="text-brick text-sm font-semibold mt-2 inline-block">View events &rarr;</span>
    </a>
</div>

<!-- Recent visits -->
<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Recent visits</h2>
        <a href="<?= url('/orgadmin/visits') ?>" class="text-sm text-brick font-semibold">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                    <th class="px-5 py-3">Visitor</th>
                    <th class="px-5 py-3">Host</th>
                    <th class="px-5 py-3">Location</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">When</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentVisits)): ?>
                    <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No visits recorded yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($recentVisits as $v): ?>
                <tr class="border-b border-ink/5 last:border-0">
                    <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['host_name'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '-') ?><?= $v['room_name'] ? ' &middot; ' . e($v['room_name']) : '' ?></td>
                    <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                    <td class="px-5 py-3 text-ink/50"><?= formatDate($v['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
