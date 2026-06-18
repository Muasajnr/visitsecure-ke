<?php
/** views/superadmin/dashboard.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Platform Dashboard</h1>
    <p class="text-sm text-ink/55 mt-1">Overview of every organisation using VisitSecure KE.</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Organisations</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $totalOrgs ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Active</p>
        <p class="font-display text-3xl font-semibold mt-1 text-verigreen"><?= $activeOrgs ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">On trial</p>
        <p class="font-display text-3xl font-semibold mt-1 text-yellow-600"><?= $trialOrgs ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Total users</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $totalUsers ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Visits today</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $totalVisitsToday ?></p>
    </div>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Recently registered organisations</h2>
        <a href="<?= url('/superadmin/organizations') ?>" class="text-sm text-brick font-semibold">View all</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Organisation</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Plan</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Joined</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentOrgs as $o): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><a href="<?= url('/superadmin/organizations/' . $o['id']) ?>" class="hover:text-brick"><?= e($o['name']) ?></a></td>
                <td class="px-5 py-3 text-ink/60"><?= e($o['email']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e($o['subscription_plan']) ?></td>
                <td class="px-5 py-3"><?= statusBadge($o['subscription_status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($o['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
