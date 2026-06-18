<header class="border-b border-ink/10 bg-paper/95 backdrop-blur sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">
        <a href="<?= url('/') ?>" class="flex items-center gap-2">
            <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
            <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/70">
            <a href="<?= url('/') ?>#features" class="hover:text-ink">Features</a>
            <a href="<?= url('/pricing') ?>" class="hover:text-ink">Pricing</a>
            <a href="<?= url('/about') ?>" class="hover:text-ink">About</a>
            <a href="<?= url('/contact') ?>" class="hover:text-ink">Contact</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="<?= url('/login') ?>" class="text-sm font-semibold text-ink hover:text-brick">Sign in</a>
            <a href="<?= url('/signup') ?>" class="text-sm font-semibold bg-ink text-white px-4 py-2 rounded-lg hover:bg-brick transition">Get started</a>
        </div>
    </div>
</header>
