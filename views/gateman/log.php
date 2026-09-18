<?php
/** views/gateman/log.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Gate Log</h1>
    <p class="text-sm text-ink/55 mt-1">All visitor activity at the gate.</p>
</div>

<form method="GET" action="<?= url('/gateman/log') ?>" class="bg-white rounded-xl border border-ink/10 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium mb-1.5 text-ink/60">Status</label>
        <select name="status" class="px-3 py-2 rounded-lg border border-ink/15 text-sm">
            <option value="">All statuses</option>
            <?php foreach (['pending','approved','rejected','checked_in','checked_out'] as $st): ?>
                <option value="<?= $st ?>" <?= $filters['status'] === $st ? 'selected' : '' ?>><?= e(ucwords(str_replace('_',' ',$st))) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium mb-1.5 text-ink/60">Date</label>
        <input type="date" name="date" value="<?= e($filters['date']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm">
    </div>
    <button type="submit" class="bg-ink text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-brick transition">Filter</button>
    <a href="<?= url('/gateman/log') ?>" class="text-sm text-ink/50 hover:text-ink">Clear</a>
</form>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                    <th class="px-5 py-3">Visitor</th>
                    <th class="px-5 py-3">Host</th>
                    <th class="px-5 py-3">Location</th>
                    <th class="px-5 py-3">In</th>
                    <th class="px-5 py-3">Out</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($visits)): ?>
                    <tr><td colspan="7" class="px-5 py-8 text-center text-ink/40">No visits found for this filter.</td></tr>
                <?php endif; ?>
                <?php foreach ($visits as $v): ?>
                <tr class="border-b border-ink/5 last:border-0">
                    <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['host_name'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '-') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></td>
                    <td class="px-5 py-3 text-ink/50"><?= formatDate($v['checked_in_at'] ?? null, 'h:i A') ?></td>
                    <td class="px-5 py-3 text-ink/50"><?= formatDate($v['checked_out_at'] ?? null, 'h:i A') ?></td>
                    <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                    <td class="px-5 py-3 text-right">
                        <?php if ($v['status'] === 'approved' && (int)($v['is_walk_in'] ?? 0) === 1): ?>
                            <form method="POST" action="<?= url('/gateman/checkin/' . $v['id']) ?>" onsubmit="return confirmAction('Check in <?= e(addslashes($v['visitor_name'])) ?>?')">
                                <?= csrfField() ?>
                                <button type="submit" class="text-green-700 font-semibold text-xs hover:underline">Check in</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
