<?php
/** views/shared/profile.php */
$initial = strtoupper(substr($user['full_name'] ?? '?', 0, 1));
?>
<div class="mb-7 flex items-center gap-4">
    <div class="w-14 h-14 rounded-full bg-ink text-white flex items-center justify-center font-display font-bold text-xl shrink-0"><?= $initial ?></div>
    <div>
        <h1 class="font-display text-2xl font-semibold leading-tight"><?= e($user['full_name'] ?? '') ?></h1>
        <p class="text-sm text-ink/50 capitalize mt-0.5"><?= e(str_replace('_', ' ', $user['role'] ?? '')) ?></p>
    </div>
</div>

<?php if ($flash = getFlash('success')): ?>
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm"><?= e($flash) ?></div>
<?php endif; ?>
<?php if ($flash = getFlash('error')): ?>
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($flash) ?></div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-5 max-w-3xl">
    <!-- Profile info -->
    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-5">Profile details</h2>
        <form method="POST" action="<?= url('/profile') ?>" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="form_action" value="update_profile">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Full name</label>
                <input type="text" name="full_name" required value="<?= e($user['full_name']) ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Email address</label>
                <input type="email" value="<?= e($user['email']) ?>" disabled
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/10 bg-ink/[0.03] text-sm text-ink/45 cursor-not-allowed">
                <p class="text-[11px] text-ink/35 mt-1">Email can't be changed. Contact support if needed.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Phone number</label>
                <input type="text" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="+254 700 000 000"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save changes</button>
        </form>
    </div>

    <!-- Change password -->
    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-1.5">Change password</h2>
        <p class="text-sm text-ink/50 mb-5">Use a strong password you don't reuse elsewhere.</p>
        <form method="POST" action="<?= url('/profile') ?>" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="form_action" value="change_password">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Current password</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">New password</label>
                <input type="password" name="new_password" required minlength="6" placeholder="At least 6 characters"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Confirm new password</label>
                <input type="password" name="confirm_password" required minlength="6"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Update password</button>
        </form>
    </div>
</div>
