<?php
/** views/superadmin/users.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">All Users</h1>
    <p class="text-sm text-ink/55 mt-1">Every user account across the platform.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3">Organisation</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr class="border-b border-ink/5 last:border-0">
                    <td class="px-5 py-3 font-medium"><?= e($u['full_name']) ?></td>
                    <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_',' ',$u['role'])) ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($u['org_name'] ?? 'Platform') ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($u['email']) ?></td>
                    <td class="px-5 py-3"><?= statusBadge($u['is_active'] ? 'active' : 'suspended') ?></td>
                    <td class="px-5 py-3 text-ink/50"><?= formatDate($u['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
