<?php $pageTitle = 'Forgot password'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12 bg-blue-50/30">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-8 h-8 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
                <span class="font-display font-semibold text-[17px]">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>
        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Reset your password</h1>
            <p class="text-sm text-ink/55 mb-6 leading-relaxed">Enter the email address on your account and we'll send you a reset link.</p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success = getFlash('success')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/forgot-password') ?>" class="space-y-4">
                <?= csrfField() ?>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Email address</label>
                    <input type="email" name="email" required value="<?= old('email') ?>" placeholder="you@company.com"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Send reset link</button>
            </form>
            <p class="text-center text-sm text-ink/50 mt-6"><a href="<?= url('/login') ?>" class="text-brick font-semibold hover:underline">&larr; Back to sign in</a></p>
        </div>
    </div>
</div>
