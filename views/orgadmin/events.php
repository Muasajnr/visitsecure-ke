<?php
/** views/orgadmin/events.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Events</h1>
        <p class="text-sm text-ink/55 mt-1">Scheduled events across your buildings — with visitor registration and room booking.</p>
    </div>
    <a href="<?= url('/events/create') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Schedule event</a>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                    <th class="px-5 py-3 font-semibold">Event</th>
                    <th class="px-5 py-3 font-semibold">Location</th>
                    <th class="px-5 py-3 font-semibold">When</th>
                    <th class="px-5 py-3 font-semibold">Registered</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <div class="flex justify-center text-ink/30"><?= icon('sparkles', 'w-8 h-8') ?></div>
                            <p class="font-display font-semibold mt-2">No events scheduled</p>
                            <p class="text-sm text-ink/45 mt-1">Create an event to give visitors a registration link and issue gate passes automatically.</p>
                            <a href="<?= url('/events/create') ?>" class="inline-block mt-4 text-brick font-semibold text-sm hover:underline">Schedule your first event &rarr;</a>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td class="px-5 py-3 font-medium"><?= e($ev['title']) ?></td>
                    <td class="px-5 py-3 text-ink/60">
                        <?= e($ev['building_name'] ?? '—') ?><?= $ev['room_name'] ? ' · ' . e($ev['room_name']) : '' ?>
                    </td>
                    <td class="px-5 py-3 text-ink/60 whitespace-nowrap">
                        <?= formatDate($ev['start_datetime']) ?>
                        <span class="text-ink/35">&ndash;</span>
                        <?= formatDate($ev['end_datetime'], 'h:i A') ?>
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-semibold tabular-nums"><?= $ev['registered_count'] ?></span>
                        <?php if ($ev['max_visitors']): ?>
                            <span class="text-ink/40"> / <?= $ev['max_visitors'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3"><?= statusBadge($ev['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
