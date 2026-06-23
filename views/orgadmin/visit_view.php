<?php
/** views/orgadmin/visit_view.php */
$v = $visit;
?>
<div class="mb-5">
    <a href="<?= url('/orgadmin/visits') ?>" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        All visits
    </a>
</div>

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($v['visitor_name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1">Visit record — admin actions below.</p>
    </div>
    <?= statusBadge($v['status']) ?>
</div>

<div class="grid md:grid-cols-2 gap-5 mb-6">
    <div class="bg-white rounded-xl border border-ink/10 p-5 space-y-3 text-sm">
        <h2 class="font-display font-semibold mb-2">Visitor</h2>
        <p><span class="text-ink/45">Name:</span> <span class="font-medium"><?= e($v['visitor_name']) ?></span></p>
        <p><span class="text-ink/45">Phone:</span> <?= e($v['visitor_phone']) ?></p>
        <?php if ($v['visitor_email']): ?><p><span class="text-ink/45">Email:</span> <?= e($v['visitor_email']) ?></p><?php endif; ?>
        <?php if ($v['visitor_company']): ?><p><span class="text-ink/45">Company:</span> <?= e($v['visitor_company']) ?></p><?php endif; ?>
        <?php if ($v['purpose']): ?><p><span class="text-ink/45">Purpose:</span> <?= e($v['purpose']) ?></p><?php endif; ?>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 space-y-3 text-sm">
        <h2 class="font-display font-semibold mb-2">Visit details</h2>
        <p><span class="text-ink/45">Host:</span> <?= e($v['host_name'] ?? '—') ?></p>
        <p><span class="text-ink/45">Location:</span> <?= e($v['building_name'] ?? '—') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></p>
        <p><span class="text-ink/45">Source:</span> <span class="capitalize"><?= e(str_replace('_', ' ', $v['source'])) ?></span><?= $v['is_walk_in'] ? ' (walk-in)' : '' ?></p>
        <p><span class="text-ink/45">Created:</span> <?= formatDate($v['created_at']) ?></p>
        <?php if ($v['scheduled_start']): ?><p><span class="text-ink/45">Scheduled:</span> <?= formatDate($v['scheduled_start']) ?></p><?php endif; ?>
        <?php if ($v['qr_code']): ?><p><span class="text-ink/45">Token:</span> <span class="font-mono text-xs"><?= e($v['qr_code']) ?></span></p><?php endif; ?>
    </div>
</div>

<?php if (!empty($v['qr_image_path'])): ?>
<div class="bg-white rounded-xl border border-ink/10 p-5 mb-6 text-center max-w-xs">
    <p class="text-xs text-ink/45 uppercase tracking-wide font-semibold mb-3">Gate pass QR</p>
    <img src="<?= e(url($v['qr_image_path'])) ?>" alt="QR code" class="w-40 h-40 mx-auto">
</div>
<?php endif; ?>

<?php if (in_array($v['status'], ['pending', 'approved'], true)): ?>
<div class="bg-white rounded-xl border border-ink/10 p-5">
    <h2 class="font-display font-semibold mb-4">Admin actions</h2>
    <div class="flex flex-wrap gap-3">
        <?php if ($v['status'] === 'pending'): ?>
        <form method="POST" action="<?= url('/orgadmin/visits/' . $v['id'] . '/approve') ?>">
            <?= csrfField() ?>
            <button type="submit" class="bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-green-800 transition">Approve &amp; send pass</button>
        </form>
        <form method="POST" action="<?= url('/orgadmin/visits/' . $v['id'] . '/reject') ?>" onsubmit="return confirmAction('Reject this visit?')">
            <?= csrfField() ?>
            <input type="hidden" name="rejected_reason" value="Rejected by organisation admin">
            <button type="submit" class="bg-white border border-brick text-brick px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-brick hover:text-white transition">Reject</button>
        </form>
        <?php endif; ?>
        <form method="POST" action="<?= url('/orgadmin/visits/' . $v['id'] . '/cancel') ?>" onsubmit="return confirmAction('Cancel this visit?')">
            <?= csrfField() ?>
            <button type="submit" class="bg-white border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-red-50 transition">Cancel visit</button>
        </form>
    </div>
</div>
<?php endif; ?>
