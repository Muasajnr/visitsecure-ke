<?php
/** views/host/dashboard.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">My Visitors</h1>
        <p class="text-sm text-ink/55 mt-1">Approve, track, and invite the people coming to see you.</p>
    </div>
    <a href="<?= url('/host/invite') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Invite a visitor</a>
</div>

<?php if (!empty($pendingVisits)): ?>
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
    <h2 class="font-display font-semibold mb-3 text-yellow-900">&#9888; Waiting for your approval (<?= count($pendingVisits) ?>)</h2>
    <div class="space-y-3">
        <?php foreach ($pendingVisits as $v): ?>
        <div class="bg-white rounded-lg p-4 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="font-medium"><?= e($v['visitor_name']) ?> <span class="text-ink/40 text-xs">(<?= e($v['visitor_phone']) ?>)</span></p>
                <p class="text-sm text-ink/55"><?= e($v['purpose'] ?? 'No purpose given') ?> &middot; <?= e($v['building_name'] ?? '') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></p>
                <?php if ($v['is_walk_in']): ?><span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-ink/5 text-xs font-medium">Walk-in at gate</span><?php endif; ?>
            </div>
            <div class="flex gap-2">
                <form method="POST" action="<?= url('/host/visits/' . $v['id'] . '/approve') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="bg-verigreen text-white px-4 py-2 rounded-lg text-xs font-semibold">Approve</button>
                </form>
                <form method="POST" action="<?= url('/host/visits/' . $v['id'] . '/reject') ?>" onsubmit="return confirmAction('Reject this visit request?')">
                    <?= csrfField() ?>
                    <button type="submit" class="bg-brick text-white px-4 py-2 rounded-lg text-xs font-semibold">Reject</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Recent visitors</h2>
        <a href="<?= url('/host/visits') ?>" class="text-sm text-brick font-semibold">View all</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Visitor</th>
                <th class="px-5 py-3">Purpose</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">When</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentVisits)): ?>
                <tr><td colspan="4" class="px-5 py-8 text-center text-ink/40">No visitors yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($recentVisits as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['purpose'] ?? '-') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
