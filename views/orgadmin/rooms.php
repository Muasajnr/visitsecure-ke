<?php
/** views/orgadmin/rooms.php */
?>
<div class="mb-5">
    <a href="<?= url('/orgadmin/buildings/' . $building['id'] . '/floors') ?>" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        <?= e($building['name']) ?> &middot; Floors
    </a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($floor['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($building['name']) ?> &middot; Rooms, offices, and firms on this floor.</p>
    </div>
    <button onclick="toggleEl('newRoomForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add room / firm</button>
</div>

<div id="newRoomForm" class="hidden bg-white rounded-xl border border-ink/10 p-6 mb-6">
    <h2 class="font-display font-semibold mb-1">New room or office</h2>
    <p class="text-sm text-ink/55 mb-5">Once added, you can assign a host to this room under Staff &amp; Users.</p>
    <form method="POST" action="<?= url('/orgadmin/floors/' . $floor['id'] . '/rooms') ?>" class="grid md:grid-cols-4 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Room name</label>
            <input type="text" name="name" required placeholder="e.g. Conference 6, Suite 4B"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Room type</label>
            <select name="room_type" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                <option value="office">Office</option>
                <option value="conference">Conference room</option>
                <option value="reception">Reception</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Firm / tenant <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="firm_name" placeholder="e.g. Cap Africa Consulting"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition w-full">Save room</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                <th class="px-5 py-3 font-semibold">Room</th>
                <th class="px-5 py-3 font-semibold">Type</th>
                <th class="px-5 py-3 font-semibold">Firm / tenant</th>
                <th class="px-5 py-3 font-semibold"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
            <?php if (empty($rooms)): ?>
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center">
                        <p class="text-ink/35 text-sm">No rooms on this floor yet.</p>
                        <p class="text-xs text-ink/30 mt-1">Add a room above, then assign a host to it under Staff &amp; Users.</p>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($rooms as $r): ?>
            <tr>
                <td class="px-5 py-3 font-medium"><?= e($r['name']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_', ' ', $r['room_type'])) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($r['firm_name'] ?? '—') ?></td>
                <td class="px-5 py-3 text-right whitespace-nowrap">
                    <button type="button" onclick="toggleEl('editRoom<?= $r['id'] ?>')" class="text-xs text-brick font-semibold hover:underline mr-3">Edit</button>
                    <form method="POST" action="<?= url('/orgadmin/rooms/' . $r['id'] . '/delete') ?>" class="inline" onsubmit="return confirmAction('Delete this room?')">
                        <?= csrfField() ?>
                        <button type="submit" class="text-xs text-red-600 font-semibold hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            <tr id="editRoom<?= $r['id'] ?>" class="hidden bg-ink/[0.02]">
                <td colspan="4" class="px-5 py-4">
                    <form method="POST" action="<?= url('/orgadmin/rooms/' . $r['id'] . '/update') ?>" class="grid md:grid-cols-4 gap-3 items-end">
                        <?= csrfField() ?>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Room name</label>
                            <input type="text" name="name" required value="<?= e($r['name']) ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Type</label>
                            <select name="room_type" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                                <?php foreach (['office','conference','reception','other'] as $rt): ?>
                                    <option value="<?= $rt ?>" <?= $r['room_type'] === $rt ? 'selected' : '' ?>><?= ucfirst($rt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 text-ink/60">Firm / tenant</label>
                            <input type="text" name="firm_name" value="<?= e($r['firm_name'] ?? '') ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                        </div>
                        <div>
                            <button type="submit" class="bg-ink text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brick transition w-full">Save</button>
                        </div>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
