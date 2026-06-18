<?php
/** views/host/visits.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">My Visitors</h1>
    <p class="text-sm text-ink/55 mt-1">Everyone who has visited or is scheduled to visit you.</p>
</div>

<div class="flex gap-2 mb-5 flex-wrap">
    <a href="<?= url('/host/visits') ?>" class="px-3 py-1.5 rounded-full text-xs font-medium <?= $status === '' ? 'bg-ink text-white' : 'bg-white border border-ink/10 text-ink/60' ?>">All</a>
    <?php foreach (['pending','approved','checked_in','checked_out','rejected'] as $st): ?>
        <a href="<?= url('/host/visits?status=' . $st) ?>" class="px-3 py-1.5 rounded-full text-xs font-medium <?= $status === $st ? 'bg-ink text-white' : 'bg-white border border-ink/10 text-ink/60' ?>"><?= e(ucwords(str_replace('_',' ',$st))) ?></a>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Visitor</th>
                <th class="px-5 py-3">Purpose</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Checked in</th>
                <th class="px-5 py-3">Checked out</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($visits)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No visitors found.</td></tr>
            <?php endif; ?>
            <?php foreach ($visits as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?><br><span class="text-ink/40 text-xs"><?= e($v['visitor_phone']) ?></span></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['purpose'] ?? '-') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['checked_in_at']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['checked_out_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
