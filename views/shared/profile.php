<?php
/** views/shared/profile.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">My Profile</h1>
    <p class="text-sm text-ink/55 mt-1">Manage your account details and password.</p>
</div>

<div class="grid md:grid-cols-2 gap-6 max-w-3xl">
    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-4">Profile details</h2>
        <form method="POST" action="<?= url('/profile') ?>" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="form_action" value="update_profile">
            <div>
                <label class="block text-sm font-medium mb-1.5">Full name</label>
                <input type="text" name="full_name" required value="<?= e($user['full_name']) ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Email</label>
                <input type="email" value="<?= e($user['email']) ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-ink/10 bg-ink/5 text-sm text-ink/50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Phone</label>
                <input type="text" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Role</label>
                <input type="text" value="<?= e(ucwords(str_replace('_',' ',$user['role']))) ?>" disabled class="w-full px-4 py-2.5 rounded-lg border border-ink/10 bg-ink/5 text-sm text-ink/50">
            </div>
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save changes</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-ink/10 p-6">
        <h2 class="font-display font-semibold mb-4">Change password</h2>
        <form method="POST" action="<?= url('/profile') ?>" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="form_action" value="change_password">
            <div>
                <label class="block text-sm font-medium mb-1.5">Current password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">New password</label>
                <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Confirm new password</label>
                <input type="password" name="confirm_password" required minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Update password</button>
        </form>
    </div>
</div>
