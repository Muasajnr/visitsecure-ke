<?php $pageTitle = 'Sign up'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12 bg-blue-50/30">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <h1 class="font-display text-2xl font-semibold text-center mb-1.5">Who are you on VisitSecure?</h1>
        <p class="text-sm text-ink/50 text-center mb-8">Pick the right account type and we'll get you set up.</p>

        <div class="grid md:grid-cols-2 gap-5">
            <a href="<?= url('/signup/organization') ?>" class="block p-7 rounded-2xl border border-ink/10 bg-white hover:border-brick/30 hover:shadow-md transition group">
                <span class="text-brick"><?= icon('building', 'w-8 h-8') ?></span>
                <h3 class="font-display font-semibold mt-3 leading-snug">I manage an organisation or building</h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed">Register your company, property, or campus. You'll be able to add buildings, floors, staff, and start issuing gate passes.</p>
                <span class="inline-block mt-5 text-brick font-semibold text-sm group-hover:underline">Register organisation &rarr;</span>
            </a>
            <a href="<?= url('/signup/visitor') ?>" class="block p-7 rounded-2xl border border-ink/10 bg-white hover:border-brick/30 hover:shadow-md transition group">
                <span class="text-brick"><?= icon('user', 'w-8 h-8') ?></span>
                <h3 class="font-display font-semibold mt-3 leading-snug">I'm visiting someone</h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed">Create a visitor account to book your own visits and receive your QR gate pass by email — no app download needed.</p>
                <span class="inline-block mt-5 text-brick font-semibold text-sm group-hover:underline">Sign up as visitor &rarr;</span>
            </a>
        </div>

        <p class="text-center text-sm text-ink/50 mt-8">
            Already have an account? <a href="<?= url('/login') ?>" class="text-brick font-semibold hover:underline">Sign in</a>
        </p>
    </div>
</div>
