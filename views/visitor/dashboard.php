<?php
/** views/visitor/dashboard.php */
$user = Auth::user();
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">
            Hi, <?= e(explode(' ', $user['full_name'] ?? 'there')[0]) ?>
        </h1>
        <p class="text-sm text-ink/55 mt-1">Your gate passes and visit history.</p>
    </div>
    <a href="<?= url('/visitor/book') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Book a visit</a>
</div>

<?php if (!empty($upcoming)): ?>
<div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
    <h2 class="font-display font-semibold mb-3 text-green-900">Upcoming visits</h2>
    <div class="space-y-3">
        <?php foreach ($upcoming as $v): ?>
        <div class="bg-white rounded-lg border border-green-100 p-4 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="font-medium"><?= e($v['host_name'] ?? 'Host TBD') ?></p>
                <p class="text-sm text-ink/55 mt-0.5">
                    <?= e($v['building_name'] ?? '') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?>
                    <?php if ($v['scheduled_start']): ?>
                        &middot; <span class="text-ink/50"><?= formatDate($v['scheduled_start']) ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <?= statusBadge($v['status']) ?>
                <?php if (in_array($v['status'], ['approved','checked_in'], true)): ?>
                    <a href="<?= url('/visitor/visits/' . $v['id'] . '/pass') ?>" class="text-brick font-semibold text-xs hover:underline">View gate pass &rarr;</a>
                <?php elseif ($v['status'] === 'pending'): ?>
                    <span class="text-xs text-ink/40">Waiting for host approval</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Visit history</h2>
        <a href="<?= url('/visitor/visits') ?>" class="text-sm text-brick font-semibold hover:underline">All <?= $total ?> visits</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                <th class="px-5 py-3 font-semibold">Host</th>
                <th class="px-5 py-3 font-semibold">Location</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">When</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
            <?php if (empty($recent)): ?>
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-ink/35 text-sm">
                        No visits yet — <a href="<?= url('/visitor/book') ?>" class="text-brick font-semibold hover:underline">book your first one</a>.
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($recent as $v): ?>
            <tr>
                <td class="px-5 py-3 font-medium"><?= e($v['host_name'] ?? '—') ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '—') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/45"><?= formatDate($v['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
