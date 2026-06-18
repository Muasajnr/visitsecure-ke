<?php $pageTitle = 'Register your organisation'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Register your organisation</h1>
            <p class="text-sm text-ink/55 mb-6">This creates your organisation account and your Organisation Admin login. You'll add buildings, floors, and staff after signing in.</p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-brick/10 border border-brick/30 text-brick text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/signup/organization') ?>" class="space-y-4">
                <?= csrfField() ?>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Organisation / building name</label>
                    <input type="text" name="org_name" required value="<?= old('org_name') ?>" placeholder="e.g. Bihi Properties Ltd"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Organisation email</label>
                    <input type="email" name="org_email" required value="<?= old('org_email') ?>" placeholder="info@yourcompany.co.ke"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Organisation phone</label>
                    <input type="text" name="org_phone" value="<?= old('org_phone') ?>" placeholder="07XX XXX XXX"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>

                <div class="border-t border-ink/10 pt-4 mt-2">
                    <p class="text-xs uppercase tracking-wide text-ink/40 font-semibold mb-3">Your admin login</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Your full name</label>
                    <input type="text" name="admin_name" required value="<?= old('admin_name') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 focus:outline-none focus:ring-2 focus:ring-ink/20 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Your email (login)</label>
                    <input type="email" name="admin_email" required value="<?= old('admin_email') ?>"
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

                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Create organisation account</button>
            </form>

            <p class="text-center text-sm text-ink/55 mt-6">
                Already registered? <a href="<?= url('/login') ?>" class="text-brick font-semibold">Sign in</a>
            </p>
        </div>
    </div>
</div>
