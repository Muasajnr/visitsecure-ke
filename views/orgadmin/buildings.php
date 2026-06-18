<?php
/** views/orgadmin/buildings.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Buildings &amp; Floors</h1>
        <p class="text-sm text-ink/55 mt-1">Set up your property structure: buildings, then floors, then rooms/firms.</p>
    </div>
    <button onclick="toggleEl('newBuildingForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add building</button>
</div>

<div id="newBuildingForm" class="hidden bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">New building</h2>
    <form method="POST" action="<?= url('/orgadmin/buildings') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium mb-1.5">Building name</label>
            <input type="text" name="name" required placeholder="e.g. Bihi Towers" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium mb-1.5">Address</label>
            <input type="text" name="address" placeholder="e.g. Nairobi CBD" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium mb-1.5">Description</label>
            <input type="text" name="description" placeholder="optional notes" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save building</button>
        </div>
    </form>
</div>

<div class="grid md:grid-cols-2 gap-4">
    <?php if (empty($buildings)): ?>
        <div class="md:col-span-2 bg-white rounded-xl border border-ink/10 p-10 text-center text-ink/50">
            No buildings yet. Click "Add building" to create your first one.
        </div>
    <?php endif; ?>
    <?php foreach ($buildings as $b): ?>
        <div class="bg-white rounded-xl border border-ink/10 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-display font-semibold text-lg"><?= e($b['name']) ?></h3>
                    <p class="text-sm text-ink/50 mt-0.5"><?= e($b['address'] ?? 'No address set') ?></p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-ink/5 text-xs font-semibold"><?= $b['floor_count'] ?> floor<?= $b['floor_count'] == 1 ? '' : 's' ?></span>
            </div>
            <?php if ($b['description']): ?><p class="text-sm text-ink/55 mt-3"><?= e($b['description']) ?></p><?php endif; ?>
            <a href="<?= url('/orgadmin/buildings/' . $b['id'] . '/floors') ?>" class="inline-block mt-4 text-brick font-semibold text-sm">Manage floors &amp; rooms &rarr;</a>
        </div>
    <?php endforeach; ?>
</div>
