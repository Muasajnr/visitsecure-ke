<?php $pageTitle = 'Sign up as a visitor'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Create your visitor account</h1>
            <p class="text-sm text-ink/55 mb-6">Use this account to book visits to any organisation on VisitSecure KE and receive your QR gate pass.</p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-brick/10 border border-brick/30 text-brick text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/signup/visitor') ?>" class="space-y-4">
                <?= csrfField() ?>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Full name</label>
                    <input type="text" name="full_name" required value="<?= old('full_name') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Email address</label>
                    <input type="email" name="email" required value="<?= old('email') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Phone number</label>
                    <input type="text" name="phone" required value="<?= old('phone') ?>" placeholder="07XX XXX XXX"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">National ID (optional)</label>
                    <input type="text" name="id_number" value="<?= old('id_number') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Confirm password</label>
                    <input type="password" name="password_confirm" required minlength="6"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create account</button>
            </form>

            <p class="text-center text-sm text-ink/55 mt-6">
                Already have an account? <a href="<?= url('/login') ?>" class="text-brick font-semibold">Sign in</a>
            </p>
        </div>
    </div>
</div>
