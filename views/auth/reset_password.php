<?php $pageTitle = 'Set new password'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12 bg-blue-50/30">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-8 h-8 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
                <span class="font-display font-semibold text-[17px]">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>
        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Choose a new password</h1>
            <p class="text-sm text-ink/55 mb-6">Pick something you haven't used before.</p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($validToken)): ?>
            <form method="POST" action="<?= url('/reset-password') ?>" class="space-y-4">
                <?= csrfField() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">New password</label>
                    <input type="password" name="password" required minlength="6" placeholder="At least 6 characters"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Confirm new password</label>
                    <input type="password" name="password_confirm" required minlength="6"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Update password</button>
            </form>
            <?php else: ?>
                <div class="px-4 py-3 rounded-lg bg-yellow-50 border border-yellow-100 text-yellow-800 text-sm">
                    This reset link is invalid or has expired.
                    <a href="<?= url('/forgot-password') ?>" class="font-semibold underline ml-1">Request a new one</a>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
