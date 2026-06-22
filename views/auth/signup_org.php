<?php $pageTitle = 'Register your organisation'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12 bg-blue-50/30">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-8 h-8 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
                <span class="font-display font-semibold text-[17px]">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm">
            <h1 class="font-display text-2xl font-semibold mb-1">Register your organisation</h1>
            <p class="text-sm text-ink/55 mb-6 leading-relaxed">
                This sets up your organisation and creates your admin login. Once you're in, you'll add your buildings, floors, and staff.
            </p>

            <?php if ($error = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/signup/organization') ?>" class="space-y-4">
                <?= csrfField() ?>

                <p class="text-xs uppercase tracking-widest text-ink/35 font-semibold pt-1">Your organisation</p>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Organisation or building name</label>
                    <input type="text" name="org_name" required value="<?= old('org_name') ?>" placeholder="e.g. Bihi Properties Ltd"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Organisation email</label>
                    <input type="email" name="org_email" required value="<?= old('org_email') ?>" placeholder="info@yourcompany.co.ke"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Phone number</label>
                    <input type="text" name="org_phone" value="<?= old('org_phone') ?>" placeholder="07XX XXX XXX"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>

                <div class="border-t border-ink/10 pt-5 mt-1">
                    <p class="text-xs uppercase tracking-widest text-ink/35 font-semibold mb-4">Your admin login</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Your full name</label>
                    <input type="text" name="admin_name" required value="<?= old('admin_name') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-ink/75">Your email address</label>
                    <input type="email" name="admin_email" required value="<?= old('admin_email') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-ink/75">Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Min. 6 characters"
                            class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-ink/75">Confirm password</label>
                        <input type="password" name="password_confirm" required minlength="6"
                            class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-300 transition">
                    </div>
                </div>

                <button type="submit" class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition mt-1">
                    Create organisation account
                </button>
            </form>

            <p class="text-center text-sm text-ink/50 mt-6">
                Already have an account? <a href="<?= url('/login') ?>" class="text-brick font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
