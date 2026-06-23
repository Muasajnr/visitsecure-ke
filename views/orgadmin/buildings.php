<?php
/** views/orgadmin/buildings.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Buildings &amp; Floors</h1>
        <p class="text-sm text-ink/55 mt-1">Set up your property structure: buildings first, then floors, then rooms.</p>
    </div>
    <button onclick="toggleEl('newBuildingForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add building</button>
</div>

<div id="newBuildingForm" class="hidden bg-white rounded-xl border border-ink/10 p-6 mb-6">
    <h2 class="font-display font-semibold mb-1">New building</h2>
    <p class="text-sm text-ink/55 mb-5">After saving, you'll be able to add floors and rooms inside this building.</p>
    <form method="POST" action="<?= url('/orgadmin/buildings') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Building name</label>
            <input type="text" name="name" required placeholder="e.g. Bihi Towers" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Address</label>
            <input type="text" name="address" placeholder="e.g. Moi Avenue, Nairobi CBD" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Notes <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="description" placeholder="e.g. 30 floors, mixed use" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save building</button>
        </div>
    </form>
</div>

<?php if (empty($buildings)): ?>
    <div class="bg-white rounded-xl border border-ink/10 p-14 text-center">
        <div class="flex justify-center text-ink/30"><?= icon('building', 'w-10 h-10') ?></div>
        <p class="font-display font-semibold mt-3">No buildings yet</p>
        <p class="text-sm text-ink/50 mt-1 max-w-xs mx-auto">Add your first building above, then set up floors and rooms inside it. Hosts are assigned to rooms, so visitors always get directed to the right place.</p>
    </div>
<?php else: ?>
    <div class="grid md:grid-cols-2 gap-4">
        <?php foreach ($buildings as $b): ?>
            <div class="bg-white rounded-xl border border-ink/10 p-5 hover:border-blue-200 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="font-display font-semibold text-lg leading-snug truncate"><?= e($b['name']) ?></h3>
                        <p class="text-sm text-ink/50 mt-0.5 truncate"><?= e($b['address'] ?? 'No address set') ?></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-ink/5 text-xs font-semibold shrink-0">
                        <?= $b['floor_count'] ?> floor<?= $b['floor_count'] == 1 ? '' : 's' ?>
                    </span>
                </div>
                <?php if ($b['description']): ?>
                    <p class="text-sm text-ink/50 mt-3 leading-relaxed"><?= e($b['description']) ?></p>
                <?php endif; ?>
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <a href="<?= url('/orgadmin/buildings/' . $b['id'] . '/floors') ?>" class="text-brick font-semibold text-sm hover:underline">
                        Manage floors &amp; rooms &rarr;
                    </a>
                    <button type="button" onclick="toggleEl('editBuilding<?= $b['id'] ?>')" class="text-sm text-ink/55 hover:text-ink font-medium">Edit</button>
                    <form method="POST" action="<?= url('/orgadmin/buildings/' . $b['id'] . '/delete') ?>" class="inline" onsubmit="return confirmAction('Delete this building and all its floors and rooms? This cannot be undone.')">
                        <?= csrfField() ?>
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Delete</button>
                    </form>
                </div>
                <div id="editBuilding<?= $b['id'] ?>" class="hidden mt-4 pt-4 border-t border-ink/10">
                    <form method="POST" action="<?= url('/orgadmin/buildings/' . $b['id'] . '/update') ?>" class="grid md:grid-cols-3 gap-3">
                        <?= csrfField() ?>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Name</label>
                            <input type="text" name="name" required value="<?= e($b['name']) ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Address</label>
                            <input type="text" name="address" value="<?= e($b['address'] ?? '') ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Notes</label>
                            <input type="text" name="description" value="<?= e($b['description'] ?? '') ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit" class="bg-ink text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brick transition">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
