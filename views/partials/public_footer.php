<footer class="bg-ink text-white/70 mt-20">
    <div class="max-w-6xl mx-auto px-5 py-12 grid md:grid-cols-4 gap-10">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <span class="w-8 h-8 rounded-md bg-brick flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
                <span class="font-display font-semibold text-white">VisitSecure KE</span>
            </div>
            <p class="text-sm text-white/50">Digital visitor management and gate-pass system for buildings and organisations across Kenya.</p>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Product</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/') ?>#features" class="hover:text-white">Features</a></li>
                <li><a href="<?= url('/pricing') ?>" class="hover:text-white">Pricing</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Company</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/about') ?>" class="hover:text-white">About</a></li>
                <li><a href="<?= url('/contact') ?>" class="hover:text-white">Contact</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Get started</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/signup/organization') ?>" class="hover:text-white">Register your organisation</a></li>
                <li><a href="<?= url('/login') ?>" class="hover:text-white">Sign in</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-xs text-white/40">
        &copy; <?= date('Y') ?> VisitSecure KE. All rights reserved.
    </div>
</footer>
