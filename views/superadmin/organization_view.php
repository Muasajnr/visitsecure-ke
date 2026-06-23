<?php
/** views/superadmin/organization_view.php */
?>
<div class="mb-5">
    <a href="<?= url('/superadmin/organizations') ?>" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        All organisations
    </a>
</div>

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($org['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($org['email']) ?> &middot; <?= e($org['phone'] ?? 'No phone on file') ?></p>
    </div>
    <?= statusBadge($org['subscription_status']) ?>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-7">
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Buildings</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['buildings'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Users</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['users'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Total visits</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['visits'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Events</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['events'] ?></p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-5 mb-6">
    <!-- Organisation details -->
    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-1">Organisation details</h2>
        <p class="text-sm text-ink/55 mb-5 leading-relaxed">Update name, contact info, and subscription plan.</p>
        <form method="POST" action="<?= url('/superadmin/organizations/' . $org['id'] . '/update') ?>" class="space-y-3">
            <?= csrfField() ?>
            <div>
                <label class="block text-xs font-medium mb-1 text-ink/60">Name</label>
                <input type="text" name="name" required value="<?= e($org['name']) ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1 text-ink/60">Email</label>
                <input type="email" name="email" required value="<?= e($org['email']) ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1 text-ink/60">Phone</label>
                <input type="text" name="phone" value="<?= e($org['phone'] ?? '') ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1 text-ink/60">Address</label>
                <input type="text" name="address" value="<?= e($org['address'] ?? '') ?>" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1 text-ink/60">Plan</label>
                <select name="subscription_plan" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm">
                    <?php foreach (['basic','standard','premium','enterprise'] as $plan): ?>
                        <option value="<?= $plan ?>" <?= $org['subscription_plan'] === $plan ? 'selected' : '' ?>><?= ucfirst($plan) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save details</button>
        </form>
    </div>

    <!-- Subscription -->
    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-1">Subscription</h2>
        <p class="text-sm text-ink/55 mb-5 leading-relaxed">Update status after confirming payment or contact. Changes take effect immediately.</p>
        <form method="POST" action="<?= url('/superadmin/organizations/' . $org['id'] . '/status') ?>" class="space-y-3">
            <?= csrfField() ?>
            <div class="flex gap-3">
                <select name="subscription_status" class="px-4 py-2.5 rounded-lg border border-ink/15 text-sm flex-1 focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                    <?php foreach (['trial','active','suspended','expired'] as $st): ?>
                        <option value="<?= $st ?>" <?= $org['subscription_status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition whitespace-nowrap">Update</button>
            </div>
        </form>
        <div class="mt-4 pt-4 border-t border-ink/10 text-sm text-ink/60 space-y-1">
            <p><span class="text-ink/40">Plan:</span> <span class="font-medium capitalize"><?= e($org['subscription_plan']) ?></span></p>
            <p><span class="text-ink/40">Registered:</span> <span class="font-medium"><?= formatDate($org['created_at']) ?></span></p>
        </div>
    </div>

    <!-- Platform actions -->
    <div class="bg-white rounded-xl border border-ink/10 p-6 md:col-span-2">
        <h2 class="font-display font-semibold mb-5">Platform actions</h2>
        <form method="POST" action="<?= url('/superadmin/organizations/' . $org['id'] . '/toggle') ?>" onsubmit="return confirmAction('Toggle this organisation\'s active status?')">
            <?= csrfField() ?>
            <button type="submit" class="border border-ink/15 px-4 py-2.5 rounded-lg text-sm font-semibold text-ink/70 hover:border-brick hover:text-brick transition">
                <?= $org['is_active'] ? 'Deactivate organisation' : 'Reactivate organisation' ?>
            </button>
        </form>
        <?php if (!$org['is_active']): ?>
            <p class="text-xs text-red-600 mt-2">This organisation is currently deactivated — users cannot log in.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Staff table -->
<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10">
        <h2 class="font-display font-semibold">Staff &amp; users</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                    <th class="px-5 py-3 font-semibold">Name</th>
                    <th class="px-5 py-3 font-semibold">Role</th>
                    <th class="px-5 py-3 font-semibold">Email</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                <?php if (empty($staff)): ?>
                    <tr><td colspan="4" class="px-5 py-10 text-center text-ink/35 text-sm">No users added to this organisation yet.</td></tr>
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
                    <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_',' ',$s['role'])) ?></td>
                    <td class="px-5 py-3 text-ink/60"><?= e($s['email']) ?></td>
                    <td class="px-5 py-3"><?= statusBadge($s['is_active'] ? 'active' : 'suspended') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
