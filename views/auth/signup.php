<?php $pageTitle = 'Sign up'; ?>
<div class="min-h-screen flex items-center justify-center px-5 py-12">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <h1 class="font-display text-2xl font-semibold text-center mb-2">How would you like to sign up?</h1>
        <p class="text-sm text-ink/55 text-center mb-8">Choose the option that matches what you need.</p>

        <div class="grid md:grid-cols-2 gap-5">
            <a href="<?= url('/signup/organization') ?>" class="block p-7 rounded-2xl border border-ink/10 bg-white hover:border-brick/40 hover:shadow-md transition">
                <span class="text-2xl">&#127970;</span>
                <h3 class="font-display font-semibold mt-3">I manage an organisation or building</h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed">Register your company, building, or property to start managing visitors, hosts, and gate passes.</p>
                <span class="inline-block mt-4 text-brick font-semibold text-sm">Register organisation &rarr;</span>
            </a>
            <a href="<?= url('/signup/visitor') ?>" class="block p-7 rounded-2xl border border-ink/10 bg-white hover:border-brick/40 hover:shadow-md transition">
                <span class="text-2xl">&#128100;</span>
                <h3 class="font-display font-semibold mt-3">I'm visiting someone</h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed">Create a visitor account so you can book your own visits and receive your QR gate pass directly.</p>
                <span class="inline-block mt-4 text-brick font-semibold text-sm">Sign up as visitor &rarr;</span>
            </a>
        </div>

        <p class="text-center text-sm text-ink/55 mt-8">
            Already have an account? <a href="<?= url('/login') ?>" class="text-brick font-semibold">Sign in</a>
        </p>
    </div>
</div>
