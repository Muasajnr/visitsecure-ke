<?php
/** views/visitor/visits.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">My Visits &amp; Passes</h1>
    <p class="text-sm text-ink/55 mt-1">All your visit requests, past and upcoming.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Host</th>
                <th class="px-5 py-3">Location</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">When</th>
                <th class="px-5 py-3">Pass</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($visits)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">You haven't booked any visits yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($visits as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['host_name'] ?? '-') ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '-') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['scheduled_start'] ?: $v['created_at']) ?></td>
                <td class="px-5 py-3">
                    <?php if (in_array($v['status'], ['approved','checked_in','checked_out'], true)): ?>
                        <a href="<?= url('/visitor/visits/' . $v['id'] . '/pass') ?>" class="text-brick font-semibold text-xs">View pass</a>
                    <?php else: ?>
                        <span class="text-ink/30 text-xs">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
