<?php
/** views/visitor/dashboard.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">My Dashboard</h1>
        <p class="text-sm text-ink/55 mt-1">Book visits and manage your gate passes.</p>
    </div>
    <a href="<?= url('/visitor/book') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Book a visit</a>
</div>

<?php if (!empty($upcoming)): ?>
<div class="bg-verigreen/5 border border-verigreen/20 rounded-xl p-5 mb-6">
    <h2 class="font-display font-semibold mb-3 text-verigreen">Upcoming visits</h2>
    <div class="space-y-3">
        <?php foreach ($upcoming as $v): ?>
        <div class="bg-white rounded-lg p-4 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="font-medium"><?= e($v['host_name'] ?? 'Host TBD') ?></p>
                <p class="text-sm text-ink/55"><?= e($v['building_name'] ?? '') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></p>
            </div>
            <div class="flex items-center gap-3">
                <?= statusBadge($v['status']) ?>
                <?php if ($v['status'] === 'approved'): ?>
                    <a href="<?= url('/visitor/visits/' . $v['id'] . '/pass') ?>" class="text-brick font-semibold text-xs">View pass &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Recent activity</h2>
        <a href="<?= url('/visitor/visits') ?>" class="text-sm text-brick font-semibold">View all (<?= $total ?>)</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Host</th>
                <th class="px-5 py-3">Location</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">When</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="4" class="px-5 py-8 text-center text-ink/40">You haven't booked any visits yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($recent as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['host_name'] ?? '-') ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '-') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
