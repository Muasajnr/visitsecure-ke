<?php $pageTitle = 'Sign in'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12 bg-blue-50/30">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Welcome back</h1>
            <p class="text-sm text-ink/50 mb-6">Enter your email and password to continue.</p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success = getFlash('success')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/login') ?>" class="space-y-4">
                <?= csrfField() ?>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/80">Email address</label>
                    <input type="email" name="email" required value="<?= old('email') ?>"
                        placeholder="you@company.com"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300 text-sm transition">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-ink/80">Password</label>
                        <a href="<?= url('/forgot-password') ?>" class="text-xs text-brick font-medium hover:underline">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300 text-sm transition">
                </div>
                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition mt-1">Sign in</button>
            </form>

            <p class="text-center text-sm text-ink/50 mt-6">
                No account yet?
                <a href="<?= url('/signup') ?>" class="text-brick font-semibold hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</div>
