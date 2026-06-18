<?php
/** views/orgadmin/settings.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Settings</h1>
    <p class="text-sm text-ink/55 mt-1">Configure your organisation's own email sender (optional). If left blank, the platform's default email sender is used.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <h2 class="font-display font-semibold mb-1">Email (SMTP) settings</h2>
    <p class="text-sm text-ink/55 mb-5">Use a Gmail address with an <strong>App Password</strong> (not your normal Gmail password). Generate one at myaccount.google.com/apppasswords after enabling 2-Step Verification.</p>

    <form method="POST" action="<?= url('/orgadmin/settings') ?>" class="space-y-4">
        <?= csrfField() ?>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">SMTP host</label>
                <input type="text" name="smtp_host" value="<?= e($settings['smtp_host'] ?? 'smtp.gmail.com') ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">SMTP port</label>
                <input type="number" name="smtp_port" value="<?= e((string)($settings['smtp_port'] ?? 587)) ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Gmail address</label>
            <input type="email" name="smtp_username" value="<?= e($settings['smtp_username'] ?? '') ?>" placeholder="yourorg@gmail.com" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Gmail App Password</label>
            <input type="text" name="smtp_app_password" value="<?= e($settings['smtp_app_password'] ?? '') ?>" placeholder="16-character app password" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm font-mono">
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">From name</label>
                <input type="text" name="smtp_from_name" value="<?= e($settings['smtp_from_name'] ?? '') ?>" placeholder="e.g. Bihi Towers Reception" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">From email</label>
                <input type="email" name="smtp_from_email" value="<?= e($settings['smtp_from_email'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save email settings</button>
    </form>
</div>
