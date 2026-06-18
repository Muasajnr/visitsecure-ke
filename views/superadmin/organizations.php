<?php
/** views/superadmin/organizations.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Organisations</h1>
        <p class="text-sm text-ink/55 mt-1">All tenants on the VisitSecure KE platform.</p>
    </div>
    <button onclick="toggleEl('newOrgForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Onboard organisation</button>
</div>

<div id="newOrgForm" class="hidden bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">New organisation</h2>
    <form method="POST" action="<?= url('/superadmin/organizations') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5">Organisation name</label>
            <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Organisation email</label>
            <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Phone</label>
            <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Admin full name</label>
            <input type="text" name="admin_name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Admin email (login)</label>
            <input type="email" name="admin_email" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Admin temporary password</label>
            <input type="text" name="admin_password" required minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create organisation</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Organisation</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Plan</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Joined</th>
                <th class="px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($organizations as $o): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($o['name']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($o['email']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e($o['subscription_plan']) ?></td>
                <td class="px-5 py-3"><?= statusBadge($o['subscription_status']) ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($o['created_at']) ?></td>
                <td class="px-5 py-3"><a href="<?= url('/superadmin/organizations/' . $o['id']) ?>" class="text-brick font-semibold text-xs">Manage</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
