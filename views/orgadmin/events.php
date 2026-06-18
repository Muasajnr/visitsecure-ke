<?php
/** views/orgadmin/events.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Events</h1>
        <p class="text-sm text-ink/55 mt-1">All events scheduled by your event managers, across all buildings.</p>
    </div>
    <a href="<?= url('/events/create') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Schedule event</a>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Event</th>
                <th class="px-5 py-3">Location</th>
                <th class="px-5 py-3">When</th>
                <th class="px-5 py-3">Registered</th>
                <th class="px-5 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($events)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No events scheduled yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($events as $ev): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($ev['title']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($ev['building_name'] ?? '-') ?><?= $ev['room_name'] ? ' · ' . e($ev['room_name']) : '' ?></td>
                <td class="px-5 py-3 text-ink/60"><?= formatDate($ev['start_datetime']) ?> &ndash; <?= formatDate($ev['end_datetime'], 'h:i A') ?></td>
                <td class="px-5 py-3 text-ink/60"><?= $ev['registered_count'] ?><?= $ev['max_visitors'] ? ' / ' . $ev['max_visitors'] : '' ?></td>
                <td class="px-5 py-3"><?= statusBadge($ev['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
