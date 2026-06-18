<?php
/** views/eventmanager/dashboard.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">My Events</h1>
        <p class="text-sm text-ink/55 mt-1">Schedule events and manage visitor registrations.</p>
    </div>
    <a href="<?= url('/events/create') ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Schedule event</a>
</div>

<div class="grid md:grid-cols-2 gap-4">
    <?php if (empty($events)): ?>
        <div class="md:col-span-2 bg-white rounded-xl border border-ink/10 p-10 text-center text-ink/50">
            You haven't scheduled any events yet.
        </div>
    <?php endif; ?>
    <?php foreach ($events as $ev): ?>
        <a href="<?= url('/events/' . $ev['id']) ?>" class="bg-white rounded-xl border border-ink/10 p-5 hover:border-brick/30 transition block">
            <div class="flex items-start justify-between">
                <h3 class="font-display font-semibold text-lg"><?= e($ev['title']) ?></h3>
                <?= statusBadge($ev['status']) ?>
            </div>
            <p class="text-sm text-ink/55 mt-1"><?= e($ev['building_name'] ?? '') ?><?= $ev['room_name'] ? ' · ' . e($ev['room_name']) : '' ?></p>
            <p class="text-sm text-ink/50 mt-2"><?= formatDate($ev['start_datetime']) ?> &ndash; <?= formatDate($ev['end_datetime'], 'h:i A') ?></p>
            <p class="text-sm font-medium mt-3"><?= $ev['registered_count'] ?> registered<?= $ev['max_visitors'] ? ' / ' . $ev['max_visitors'] . ' max' : '' ?></p>
        </a>
    <?php endforeach; ?>
</div>
