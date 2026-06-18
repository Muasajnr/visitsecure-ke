<?php
/** views/orgadmin/users.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Staff &amp; Users</h1>
        <p class="text-sm text-ink/55 mt-1">Manage gatemen, hosts, and event managers for your organisation.</p>
    </div>
    <button onclick="toggleEl('newStaffForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add staff member</button>
</div>

<div id="newStaffForm" class="hidden bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">New staff account</h2>
    <form method="POST" action="<?= url('/orgadmin/users') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5">Full name</label>
            <input type="text" name="full_name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Email</label>
            <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Phone</label>
            <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Role</label>
            <select name="role" id="roleSelect" onchange="document.getElementById('roomField').classList.toggle('hidden', this.value !== 'host')" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                <option value="gateman">Gateman / Security</option>
                <option value="host">Host (assigned to a room)</option>
                <option value="event_manager">Event Manager</option>
            </select>
        </div>
        <div id="roomField">
            <label class="block text-sm font-medium mb-1.5">Assign to room (for hosts)</label>
            <select name="room_id" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                <option value="">-- Select room --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= e($r['building_name']) ?> &middot; <?= e($r['floor_name']) ?> &middot; <?= e($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Temporary password</label>
            <input type="text" name="password" required minlength="6" placeholder="At least 6 characters" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create account</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Role</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No staff added yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($staff as $s): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($s['full_name']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_', ' ', $s['role'])) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($s['email']) ?></td>
                <td class="px-5 py-3"><?= statusBadge($s['is_active'] ? 'active' : 'suspended') ?></td>
                <td class="px-5 py-3">
                    <?php if ($s['role'] !== 'org_admin'): ?>
                    <form method="POST" action="<?= url('/orgadmin/users/' . $s['id'] . '/toggle') ?>" onsubmit="return confirmAction('Change this user\'s status?')">
                        <?= csrfField() ?>
                        <button type="submit" class="text-brick font-semibold text-xs"><?= $s['is_active'] ? 'Deactivate' : 'Activate' ?></button>
                    </form>
                    <?php else: ?>
                        <span class="text-ink/30 text-xs">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
