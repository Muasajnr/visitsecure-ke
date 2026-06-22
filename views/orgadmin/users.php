<?php
/** views/orgadmin/users.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Staff &amp; Users</h1>
        <p class="text-sm text-ink/55 mt-1">Gatemen, hosts, and event managers for your organisation.</p>
    </div>
    <button onclick="toggleEl('newStaffForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Add staff member</button>
</div>

<div id="newStaffForm" class="hidden bg-white rounded-xl border border-ink/10 p-6 mb-6">
    <h2 class="font-display font-semibold mb-1">New staff account</h2>
    <p class="text-sm text-ink/55 mb-5">They'll log in with this email. Send them the temporary password separately — they can change it from their profile.</p>
    <form method="POST" action="<?= url('/orgadmin/users') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Full name</label>
            <input type="text" name="full_name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Email address</label>
            <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Phone number</label>
            <input type="text" name="phone" placeholder="07XX XXX XXX" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Role</label>
            <select name="role" id="roleSelect" onchange="document.getElementById('roomField').classList.toggle('hidden', this.value !== 'host')" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                <option value="gateman">Gateman / Security</option>
                <option value="host">Host (tied to a room)</option>
                <option value="event_manager">Event Manager</option>
            </select>
        </div>
        <div id="roomField" class="hidden">
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Assign to room</label>
            <select name="room_id" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                <option value="">— Select room —</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= e($r['building_name']) ?> · <?= e($r['floor_name']) ?> · <?= e($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($rooms)): ?>
                <p class="text-[11px] text-brick mt-1.5">No rooms set up yet. Add buildings and floors first.</p>
            <?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Temporary password</label>
            <input type="text" name="password" required minlength="6" placeholder="At least 6 characters" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create account</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                <th class="px-5 py-3 font-semibold">Name</th>
                <th class="px-5 py-3 font-semibold">Role</th>
                <th class="px-5 py-3 font-semibold">Email</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
            <?php if (empty($staff)): ?>
                <tr>
                    <td colspan="5" class="px-5 py-14 text-center text-sm text-ink/40">
                        No staff added yet.<br>
                        <span class="text-xs mt-1 inline-block">Add gatemen, hosts, and event managers using the button above.</span>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($staff as $s): ?>
            <tr>
                <td class="px-5 py-3 font-medium">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full bg-ink/8 text-ink/50 font-semibold text-xs flex items-center justify-center shrink-0">
                            <?= strtoupper(substr($s['full_name'], 0, 1)) ?>
                        </span>
                        <?= e($s['full_name']) ?>
                    </div>
                </td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_', ' ', $s['role'])) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($s['email']) ?></td>
                <td class="px-5 py-3"><?= statusBadge($s['is_active'] ? 'active' : 'suspended') ?></td>
                <td class="px-5 py-3 text-right">
                    <?php if ($s['role'] !== 'org_admin'): ?>
                    <form method="POST" action="<?= url('/orgadmin/users/' . $s['id'] . '/toggle') ?>" onsubmit="return confirmAction('Change this user\'s status?')">
                        <?= csrfField() ?>
                        <button type="submit" class="text-xs text-brick font-semibold hover:underline"><?= $s['is_active'] ? 'Deactivate' : 'Activate' ?></button>
                    </form>
                    <?php else: ?>
                        <span class="text-ink/25 text-xs">admin</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
