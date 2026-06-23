<?php
/** views/eventmanager/view.php */
?>
<div class="mb-5">
    <a href="<?= url('/events/dashboard') ?>" class="text-sm text-ink/50 hover:text-ink">&larr; All events</a>
</div>

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($event['title']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($event['building_name'] ?? '') ?><?= $event['room_name'] ? ' · ' . e($event['room_name']) : '' ?></p>
        <p class="text-sm text-ink/50 mt-1"><?= formatDate($event['start_datetime']) ?> &ndash; <?= formatDate($event['end_datetime'], 'h:i A') ?></p>
    </div>
    <div class="flex items-center gap-3">
        <?= statusBadge($event['status']) ?>
        <?php if ($event['status'] !== 'cancelled'): ?>
        <a href="<?= url('/events/' . $event['id'] . '/edit') ?>" class="text-sm text-brick font-semibold hover:underline">Edit event</a>
        <form method="POST" action="<?= url('/events/' . $event['id'] . '/cancel') ?>" onsubmit="return confirmAction('Cancel this event?')">
            <?= csrfField() ?>
            <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Cancel</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($event['description']): ?>
<div class="bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <p class="text-sm text-ink/65"><?= nl2br(e($event['description'])) ?></p>
</div>
<?php endif; ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="font-display font-semibold">Registered visitors (<?= count($visitors) ?><?= $event['max_visitors'] ? ' / ' . $event['max_visitors'] : '' ?>)</h2>
    <a href="<?= url('/events/' . $event['id'] . '/register') ?>" class="bg-ink text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Register a visitor</a>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Company</th>
                <th class="px-5 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($visitors)): ?>
                <tr><td colspan="4" class="px-5 py-8 text-center text-ink/40">No one has registered for this event yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($visitors as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['visitor_phone']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['visitor_company'] ?? '-') ?></td>
                <td class="px-5 py-3"><?= statusBadge($v['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
