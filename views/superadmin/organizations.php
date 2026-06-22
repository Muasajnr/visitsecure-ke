<?php
/** views/superadmin/organizations.php */
?>
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Organisations</h1>
        <p class="text-sm text-ink/55 mt-1">Every tenant on the platform. Click to manage subscription, users, and settings.</p>
    </div>
    <button onclick="toggleEl('newOrgForm')" class="bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">+ Onboard organisation</button>
</div>

<div id="newOrgForm" class="hidden bg-white rounded-xl border border-ink/10 p-6 mb-6">
    <h2 class="font-display font-semibold mb-1">Onboard new organisation</h2>
    <p class="text-sm text-ink/55 mb-5 leading-relaxed">Creates the organisation account and its first admin login. Send the admin their temporary password separately — they should change it on first login.</p>
    <form method="POST" action="<?= url('/superadmin/organizations') ?>" class="grid md:grid-cols-3 gap-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Organisation name</label>
            <input type="text" name="name" required placeholder="e.g. Bihi Properties Ltd"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Organisation email</label>
            <input type="email" name="email" required placeholder="info@org.co.ke"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Phone <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="phone" placeholder="07XX XXX XXX"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Admin full name</label>
            <input type="text" name="admin_name" required
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Admin email (login)</label>
            <input type="email" name="admin_email" required
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Temporary password</label>
            <input type="text" name="admin_password" required minlength="6" placeholder="At least 6 characters"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create organisation</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                    <th class="px-5 py-3 font-semibold">Organisation</th>
                    <th class="px-5 py-3 font-semibold">Email</th>
                    <th class="px-5 py-3 font-semibold">Plan</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                <?php if (empty($organizations)): ?>
                    <tr><td colspan="6" class="px-5 py-10 text-center text-ink/35 text-sm">No organisations onboarded yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($organizations as $o): ?>
                <tr>
                    <td class="px-5 py-3 font-medium"><?= e($o['name']) ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($o['email']) ?></td>
                    <td class="px-5 py-3 text-ink/60 capitalize"><?= e($o['subscription_plan']) ?></td>
                    <td class="px-5 py-3"><?= statusBadge($o['subscription_status']) ?></td>
                    <td class="px-5 py-3 text-ink/45"><?= formatDate($o['created_at']) ?></td>
                    <td class="px-5 py-3 text-right">
                        <a href="<?= url('/superadmin/organizations/' . $o['id']) ?>" class="text-brick font-semibold text-xs hover:underline">Manage</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
