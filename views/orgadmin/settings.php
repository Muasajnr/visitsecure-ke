<?php
/** views/orgadmin/settings.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Settings</h1>
    <p class="text-sm text-ink/55 mt-1">Configure how your organisation sends emails to visitors and staff.</p>
</div>

<?php if ($flash = getFlash('success')): ?>
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm max-w-2xl"><?= e($flash) ?></div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <div class="flex items-start gap-3 mb-6 pb-6 border-b border-ink/10">
        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 text-blue-600"><?= icon('envelope', 'w-5 h-5') ?></div>
        <div>
            <h2 class="font-display font-semibold">Email (SMTP) settings</h2>
            <p class="text-sm text-ink/55 mt-1 leading-relaxed">
                Use a Gmail address and an <strong>App Password</strong> — not your normal Gmail password.
                Generate one at <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-brick underline">myaccount.google.com/apppasswords</a> after enabling 2-Step Verification.
                If you leave this blank, the platform's default sender is used.
            </p>
        </div>
    </div>

    <form method="POST" action="<?= url('/orgadmin/settings') ?>" class="space-y-4">
        <?= csrfField() ?>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">SMTP host</label>
                <input type="text" name="smtp_host" value="<?= e($settings['smtp_host'] ?? 'smtp.gmail.com') ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">SMTP port</label>
                <input type="number" name="smtp_port" value="<?= e((string)($settings['smtp_port'] ?? 587)) ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Gmail address</label>
            <input type="email" name="smtp_username" value="<?= e($settings['smtp_username'] ?? '') ?>" placeholder="yourorg@gmail.com"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Gmail App Password</label>
            <input type="text" name="smtp_app_password" value="<?= e($settings['smtp_app_password'] ?? '') ?>" placeholder="xxxx xxxx xxxx xxxx"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm font-mono tracking-widest focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            <p class="text-[11px] text-ink/40 mt-1">16 characters, usually shown in groups of 4.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">From name</label>
                <input type="text" name="smtp_from_name" value="<?= e($settings['smtp_from_name'] ?? '') ?>" placeholder="e.g. Bihi Towers Reception"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">From email</label>
                <input type="email" name="smtp_from_email" value="<?= e($settings['smtp_from_email'] ?? '') ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
            </div>
        </div>
        <div class="pt-2">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save email settings</button>
        </div>
    </form>
</div>
