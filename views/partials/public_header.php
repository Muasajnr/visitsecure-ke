<header class="border-b border-ink/10 bg-white/95 backdrop-blur sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">
        <a href="<?= url('/') ?>" class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
            <span class="font-display font-semibold text-[17px] tracking-tight">VisitSecure <span class="text-brick">KE</span></span>
        </a>
        <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-ink/65">
            <a href="<?= url('/') ?>#features" class="hover:text-ink transition">Features</a>
            <a href="<?= url('/pricing') ?>" class="hover:text-ink transition">Pricing</a>
            <a href="<?= url('/about') ?>" class="hover:text-ink transition">About</a>
            <a href="<?= url('/contact') ?>" class="hover:text-ink transition">Contact</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="<?= url('/login') ?>" class="text-sm font-semibold text-ink/70 hover:text-ink transition">Sign in</a>
            <a href="<?= url('/signup') ?>" class="text-sm font-semibold bg-ink text-white px-4 py-2 rounded-lg hover:bg-brick transition">Get started</a>
        </div>
    </div>
</header>
