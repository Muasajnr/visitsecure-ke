<?php
/** views/orgadmin/floors.php */
?>
<div class="mb-5">
    <a href="<?= url('/orgadmin/buildings') ?>" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        All buildings
    </a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($building['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($building['address'] ?? 'Manage floors and rooms for this building.') ?></p>
    </div>
    <button onclick="toggleEl('newFloorForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add floor</button>
</div>

<div id="newFloorForm" class="hidden bg-white rounded-xl border border-ink/10 p-6 mb-6">
    <h2 class="font-display font-semibold mb-1">New floor</h2>
    <p class="text-sm text-ink/55 mb-5">After adding the floor, you'll be able to add rooms and assign hosts to them.</p>
    <form method="POST" action="<?= url('/orgadmin/buildings/' . $building['id'] . '/floors') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Floor name</label>
            <input type="text" name="name" required placeholder="e.g. Ground Floor, Floor 6"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Floor number <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="number" name="floor_number" placeholder="e.g. 6"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition w-full">Save floor</button>
        </div>
    </form>
</div>

<?php if (empty($floors)): ?>
    <div class="bg-white rounded-xl border border-ink/10 p-14 text-center">
        <div class="flex justify-center text-ink/30"><?= icon('layers', 'w-10 h-10') ?></div>
        <p class="font-display font-semibold mt-3">No floors yet</p>
        <p class="text-sm text-ink/50 mt-1 max-w-xs mx-auto">Add your first floor above, then add rooms and assign hosts to them. Visitors need a specific room to be directed to.</p>
    </div>
<?php else: ?>
    <div class="grid md:grid-cols-2 gap-4">
        <?php foreach ($floors as $f): ?>
            <div class="bg-white rounded-xl border border-ink/10 p-5 hover:border-blue-200 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="font-display font-semibold text-lg"><?= e($f['name']) ?></h3>
                    <span class="px-2.5 py-1 rounded-full bg-ink/5 text-xs font-semibold shrink-0">
                        <?= $f['room_count'] ?> room<?= $f['room_count'] == 1 ? '' : 's' ?>
                    </span>
                </div>
                <a href="<?= url('/orgadmin/floors/' . $f['id'] . '/rooms') ?>" class="inline-block mt-4 text-brick font-semibold text-sm hover:underline">
                    Manage rooms &amp; firms &rarr;
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
