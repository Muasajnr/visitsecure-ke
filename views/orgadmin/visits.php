<?php
/** views/orgadmin/visits.php */
$hasFilters = $filters['status'] || $filters['building_id'] || $filters['date'];
?>
<div class="mb-6 flex items-start justify-between flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">All Visits</h1>
        <p class="text-sm text-ink/55 mt-1">Every visit booked, checked in, or completed across your organisation.</p>
    </div>
</div>

<form method="GET" action="<?= url('/orgadmin/visits') ?>" class="bg-white rounded-xl border border-ink/10 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium mb-1.5 text-ink/60">Status</label>
        <select name="status" class="px-3 py-2 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            <option value="">All statuses</option>
            <?php foreach (['pending','approved','rejected','checked_in','checked_out','expired','cancelled'] as $st): ?>
                <option value="<?= $st ?>" <?= $filters['status'] === $st ? 'selected' : '' ?>><?= e(ucwords(str_replace('_',' ',$st))) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium mb-1.5 text-ink/60">Building</label>
        <select name="building_id" class="px-3 py-2 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            <option value="">All buildings</option>
            <?php foreach ($buildings as $b): ?>
                <option value="<?= $b['id'] ?>" <?= (string)$filters['building_id'] === (string)$b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium mb-1.5 text-ink/60">Date</label>
        <input type="date" name="date" value="<?= e($filters['date']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
    </div>
    <div class="flex items-center gap-2">
        <button type="submit" class="bg-ink text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-brick transition">Filter</button>
        <?php if ($hasFilters): ?>
            <a href="<?= url('/orgadmin/visits') ?>" class="text-sm text-ink/50 hover:text-ink">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                    <th class="px-5 py-3 font-semibold">Visitor</th>
                    <th class="px-5 py-3 font-semibold">Host</th>
                    <th class="px-5 py-3 font-semibold">Location</th>
                    <th class="px-5 py-3 font-semibold">Source</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold">Created</th>
                    <th class="px-5 py-3 font-semibold"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                <?php if (empty($visits)): ?>
                    <tr>
                        <td colspan="7" class="px-5 py-14 text-center">
                            <p class="text-ink/40 text-sm">
                                <?= $hasFilters ? 'No visits match those filters — try clearing some.' : 'No visits recorded yet. They\'ll appear here once your gateman starts checking people in.' ?>
                            </p>
                            <?php if ($hasFilters): ?>
                                <a href="<?= url('/orgadmin/visits') ?>" class="text-brick text-xs font-semibold mt-2 inline-block hover:underline">Clear all filters</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($visits as $v): ?>
                <tr>
                    <td class="px-5 py-3 font-medium">
                        <?= e($v['visitor_name']) ?>
                        <?php if ($v['visitor_phone']): ?><br><span class="text-ink/40 text-xs"><?= e($v['visitor_phone']) ?></span><?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['host_name'] ?? '—') ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '—') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></td>
                    <td class="px-5 py-3 text-ink/50 capitalize"><?= e(str_replace('_',' ',$v['source'])) ?><?= $v['is_walk_in'] ? ' <span class="text-[10px] bg-ink/5 rounded px-1">walk-in</span>' : '' ?></td>
                    <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                    <td class="px-5 py-3 text-ink/45"><?= formatDate($v['created_at']) ?></td>
                    <td class="px-5 py-3 text-right">
                        <a href="<?= url('/orgadmin/visits/' . $v['id']) ?>" class="text-xs text-brick font-semibold hover:underline">Manage</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
