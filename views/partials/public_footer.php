<footer class="bg-ink text-white/65 mt-24">
    <div class="max-w-6xl mx-auto px-5 py-12 grid md:grid-cols-4 gap-10">
        <div class="md:col-span-1">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-md bg-brick flex items-center justify-center text-white font-display font-bold text-xs">VS</span>
                <span class="font-display font-semibold text-white text-sm">VisitSecure KE</span>
            </div>
            <p class="text-sm text-white/45 leading-relaxed">Digital visitor management for buildings and organisations across Kenya.</p>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Product</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/') ?>#features" class="hover:text-white transition">Features</a></li>
                <li><a href="<?= url('/pricing') ?>" class="hover:text-white transition">Pricing</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Company</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/about') ?>" class="hover:text-white transition">About</a></li>
                <li><a href="<?= url('/contact') ?>" class="hover:text-white transition">Contact</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display font-semibold text-white mb-3 text-sm">Get started</p>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= url('/signup/organization') ?>" class="hover:text-white transition">Register your organisation</a></li>
                <li><a href="<?= url('/signup/visitor') ?>" class="hover:text-white transition">Visitor sign-up</a></li>
                <li><a href="<?= url('/login') ?>" class="hover:text-white transition">Sign in</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-xs text-white/35">
        &copy; <?= date('Y') ?> VisitSecure KE &mdash; Nairobi, Kenya
    </div>
</footer>
