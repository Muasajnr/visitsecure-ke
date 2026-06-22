<?php
/** views/host/dashboard.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">My visitors</h1>
        <p class="text-sm text-ink/55 mt-1">Approve requests, track who's on their way, and invite people directly.</p>
    </div>
    <a href="<?= url('/host/invite') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Invite a visitor</a>
</div>

<?php if (!empty($pendingVisits)): ?>
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
    <h2 class="font-display font-semibold mb-3 text-yellow-900 flex items-center gap-2">
        <?= icon('warning', 'w-5 h-5 shrink-0 text-yellow-700') ?>
        <span>Waiting for your approval (<?= count($pendingVisits) ?>)</span>
    </h2>
    <div class="space-y-3">
        <?php foreach ($pendingVisits as $v): ?>
        <div class="bg-white rounded-lg border border-yellow-100 p-4 flex items-start justify-between flex-wrap gap-3">
            <div class="min-w-0">
                <p class="font-medium">
                    <?= e($v['visitor_name']) ?>
                    <?php if ($v['visitor_phone']): ?>
                        <span class="text-ink/40 text-xs ml-1">(<?= e($v['visitor_phone']) ?>)</span>
                    <?php endif; ?>
                </p>
                <p class="text-sm text-ink/55 mt-0.5 truncate">
                    <?= e($v['purpose'] ?? 'No purpose stated') ?>
                    <?php if ($v['building_name']): ?>&middot; <?= e($v['building_name']) ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?><?php endif; ?>
                </p>
                <?php if ($v['is_walk_in']): ?>
                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full bg-ink/5 text-ink/50 text-[11px] font-medium">Walk-in at gate</span>
                <?php endif; ?>
            </div>
            <div class="flex gap-2 shrink-0">
                <form method="POST" action="<?= url('/host/visits/' . $v['id'] . '/approve') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-green-800 transition">Approve</button>
                </form>
                <form method="POST" action="<?= url('/host/visits/' . $v['id'] . '/reject') ?>" onsubmit="return confirmAction('Reject this visit request?')">
                    <?= csrfField() ?>
                    <button type="submit" class="bg-white border border-brick text-brick px-4 py-2 rounded-lg text-xs font-semibold hover:bg-brick hover:text-white transition">Reject</button>
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
        <a href="<?= url('/host/visits') ?>" class="text-sm text-brick font-semibold hover:underline">View all</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                <th class="px-5 py-3 font-semibold">Visitor</th>
                <th class="px-5 py-3 font-semibold">Purpose</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">When</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
            <?php if (empty($recentVisits)): ?>
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-ink/35 text-sm">
                        No visitors yet — invite someone or wait for a request.
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($recentVisits as $v): ?>
            <tr>
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['purpose'] ?? '—') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/45"><?= formatDate($v['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
