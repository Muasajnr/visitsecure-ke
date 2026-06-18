<?php
/** views/superadmin/organization_view.php */
?>
<div class="mb-5">
    <a href="<?= url('/superadmin/organizations') ?>" class="text-sm text-ink/50 hover:text-ink">&larr; All organisations</a>
</div>

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold"><?= e($org['name']) ?></h1>
        <p class="text-sm text-ink/55 mt-1"><?= e($org['email']) ?> &middot; <?= e($org['phone'] ?? 'No phone') ?></p>
    </div>
    <?= statusBadge($org['subscription_status']) ?>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Buildings</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['buildings'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Users</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['users'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Total visits</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['visits'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Events</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $counts['events'] ?></p>
    </div>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 mb-6 max-w-lg">
    <h2 class="font-display font-semibold mb-4">Subscription management</h2>
    <p class="text-sm text-ink/55 mb-4">Update this organisation's subscription status after confirming payment offline.</p>
    <form method="POST" action="<?= url('/superadmin/organizations/' . $org['id'] . '/status') ?>" class="flex gap-3">
        <?= csrfField() ?>
        <select name="subscription_status" class="px-4 py-2.5 rounded-lg border border-ink/15 text-sm flex-1">
            <?php foreach (['trial','active','suspended','expired'] as $st): ?>
                <option value="<?= $st ?>" <?= $org['subscription_status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Update</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10">
        <h2 class="font-display font-semibold">Staff &amp; users</h2>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Role</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)): ?>
                <tr><td colspan="4" class="px-5 py-8 text-center text-ink/40">No users yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($staff as $s): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($s['full_name']) ?></td>
                <td class="px-5 py-3 text-ink/60 capitalize"><?= e(str_replace('_',' ',$s['role'])) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($s['email']) ?></td>
                <td class="px-5 py-3"><?= statusBadge($s['is_active'] ? 'active' : 'suspended') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
