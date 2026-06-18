<?php
/** views/orgadmin/rooms.php */
?>
<div class="mb-5">
    <a href="<?= url('/orgadmin/buildings/' . $building['id'] . '/floors') ?>" class="text-sm text-ink/50 hover:text-ink">&larr; <?= e($building['name']) ?> floors</a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($building['name']) ?> &middot; <?= e($floor['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1">Rooms, offices, and firms on this floor.</p>
    </div>
    <button onclick="toggleEl('newRoomForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add room / firm</button>
</div>

<div id="newRoomForm" class="hidden bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">New room / office</h2>
    <form method="POST" action="<?= url('/orgadmin/floors/' . $floor['id'] . '/rooms') ?>" class="grid md:grid-cols-4 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5">Room name</label>
            <input type="text" name="name" required placeholder="e.g. Conference 6 / Suite 4B" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Type</label>
            <select name="room_type" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                <option value="office">Office</option>
                <option value="conference">Conference room</option>
                <option value="reception">Reception</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Firm occupying it (optional)</label>
            <input type="text" name="firm_name" placeholder="e.g. Cap Africa Consulting" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition w-full">Save room</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Room name</th>
                <th class="px-5 py-3">Type</th>
                <th class="px-5 py-3">Firm</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rooms)): ?>
                <tr><td colspan="3" class="px-5 py-8 text-center text-ink/40">No rooms yet on this floor.</td></tr>
            <?php endif; ?>
            <?php foreach ($rooms as $r): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($r['name']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e($r['room_type']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($r['firm_name'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
