<?php
/** views/orgadmin/floors.php */
?>
<div class="mb-5">
    <a href="<?= url('/orgadmin/buildings') ?>" class="text-sm text-ink/50 hover:text-ink">&larr; All buildings</a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($building['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($building['address'] ?? '') ?></p>
    </div>
    <button onclick="toggleEl('newFloorForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add floor</button>
</div>

<div id="newFloorForm" class="hidden bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">New floor</h2>
    <form method="POST" action="<?= url('/orgadmin/buildings/' . $building['id'] . '/floors') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5">Floor name</label>
            <input type="text" name="name" required placeholder="e.g. 1st Floor / Floor 6" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Floor number (optional)</label>
            <input type="number" name="floor_number" placeholder="e.g. 6" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition w-full">Save floor</button>
        </div>
    </form>
</div>

<div class="grid md:grid-cols-2 gap-4">
    <?php if (empty($floors)): ?>
        <div class="md:col-span-2 bg-white rounded-xl border border-ink/10 p-10 text-center text-ink/50">
            No floors yet for this building. Click "Add floor" to create one.
        </div>
    <?php endif; ?>
    <?php foreach ($floors as $f): ?>
        <div class="bg-white rounded-xl border border-ink/10 p-5">
            <div class="flex items-start justify-between">
                <h3 class="font-display font-semibold text-lg"><?= e($f['name']) ?></h3>
                <span class="px-2.5 py-1 rounded-full bg-ink/5 text-xs font-semibold"><?= $f['room_count'] ?> room<?= $f['room_count'] == 1 ? '' : 's' ?></span>
            </div>
            <a href="<?= url('/orgadmin/floors/' . $f['id'] . '/rooms') ?>" class="inline-block mt-4 text-brick font-semibold text-sm">Manage rooms / firms &rarr;</a>
        </div>
    <?php endforeach; ?>
</div>
