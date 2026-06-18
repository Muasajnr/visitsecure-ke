<?php $pageTitle = 'Home'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<!-- HERO -->
<section class="max-w-6xl mx-auto px-5 pt-16 pb-20 grid md:grid-cols-2 gap-12 items-center">
    <div>
        <span class="inline-block px-3 py-1 rounded-full bg-verigreen/10 text-verigreen text-xs font-semibold tracking-wide uppercase mb-5">Built for Kenyan buildings &amp; organisations</span>
        <h1 class="font-display text-4xl md:text-5xl font-semibold leading-tight tracking-tight">
            Replace the visitor book.<br>Give every guest a <span class="text-brick">gate pass</span>.
        </h1>
        <p class="mt-5 text-ink/60 text-lg leading-relaxed max-w-md">
            VisitSecure KE turns the front-desk register into a QR-coded check-in system &mdash; for single offices, full towers, and the firms on every floor.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="<?= url('/signup/organization') ?>" class="bg-brick text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-brick/90 transition">Register your organisation</a>
            <a href="<?= url('/signup/visitor') ?>" class="border border-ink/15 px-6 py-3 rounded-lg font-semibold text-sm hover:border-ink/30 transition">I'm a visitor</a>
        </div>
        <div class="mt-10 flex items-center gap-6 text-sm text-ink/50">
            <div><span class="font-display text-2xl font-semibold text-ink">Multi-tenant</span><br>one platform, many organisations</div>
            <div class="w-px h-10 bg-ink/10"></div>
            <div><span class="font-display text-2xl font-semibold text-ink">Floor-aware</span><br>building &rarr; floor &rarr; room</div>
        </div>
    </div>

    <!-- Signature element: a mock gate pass card -->
    <div class="relative">
        <div class="absolute -inset-4 bg-ink/5 rounded-3xl rotate-2"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-ink/10 p-6 max-w-sm mx-auto">
            <div class="flex items-center justify-between mb-4">
                <span class="font-display font-semibold text-sm">Gate Pass</span>
                <span class="px-2 py-0.5 rounded-full bg-verigreen/10 text-verigreen text-xs font-semibold">Approved</span>
            </div>
            <div class="text-xs text-ink/40 font-mono tracking-wider mb-1">Bihi Towers &rarr; Floor 6 &rarr; Conference 6</div>
            <div class="border-t border-dashed border-ink/15 my-4"></div>
            <div class="flex gap-4 items-center">
                <div class="w-24 h-24 bg-ink/5 rounded-lg grid grid-cols-4 grid-rows-4 gap-0.5 p-2 shrink-0">
                    <?php for ($i = 0; $i < 16; $i++): ?>
                        <div class="<?= rand(0,1) ? 'bg-ink' : 'bg-transparent' ?> rounded-sm"></div>
                    <?php endfor; ?>
                </div>
                <div class="text-sm">
                    <p class="font-semibold">Achieng Otieno</p>
                    <p class="text-ink/50 text-xs mt-0.5">Visiting: Wanjiru Kamau</p>
                    <p class="text-ink/50 text-xs">Cap Africa Consulting</p>
                    <p class="font-mono text-[11px] text-ink/40 mt-2">TOKEN A1F9-22DC-77BE</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="max-w-6xl mx-auto px-5 py-16 border-t border-ink/10">
    <div class="max-w-xl mb-12">
        <h2 class="font-display text-3xl font-semibold">Everything the front desk needs</h2>
        <p class="text-ink/60 mt-3">From a single-room office to a multi-floor tower with dozens of firms, VisitSecure KE adapts to how your building is actually organised.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        <?php
        $features = [
            ['&#127970;', 'Buildings, floors &amp; rooms', 'Model your property exactly as it is: building &rarr; floor &rarr; room/firm &rarr; host. Built for towers like Bihi Towers with many firms per floor.'],
            ['&#9989;', 'QR check-in &amp; check-out', 'Every approved visit gets a unique QR gate pass. Gatemen scan at the entrance, no typing, no register.'],
            ['&#128221;', 'Walk-in registration', 'Unregistered visitors can be registered on the spot by the gateman and instantly request host approval.'],
            ['&#128197;', 'Event scheduling', 'Event managers schedule a conference in a specific room and let visitors self-register or be added directly.'],
            ['&#128231;', 'Email alerts via SMTP', 'Hosts and visitors get notified by email (Gmail SMTP + app password) and in-app the moment a pass is issued.'],
            ['&#128202;', 'Analytics &amp; reporting', 'Daily traffic, peak hours, and visit history per building, floor, or organisation.'],
        ];
        foreach ($features as $f): ?>
            <div class="p-6 rounded-xl border border-ink/10 hover:border-brick/30 hover:shadow-sm transition">
                <span class="text-2xl"><?= $f[0] ?></span>
                <h3 class="font-display font-semibold mt-3"><?= $f[1] ?></h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed"><?= $f[2] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="max-w-6xl mx-auto px-5 py-16 border-t border-ink/10">
    <h2 class="font-display text-3xl font-semibold mb-10">How a visit happens</h2>
    <div class="grid md:grid-cols-4 gap-6 text-sm">
        <?php
        $steps = [
            ['Book', 'A visitor, host, or event manager creates a visit and picks the exact room and time.'],
            ['Approve', 'The host approves or rejects the request from their dashboard or email.'],
            ['Scan', 'On arrival, the gateman scans the visitor\'s QR gate pass to check them in.'],
            ['Check out', 'The gateman checks the visitor out when they leave; the full trail is logged.'],
        ];
        foreach ($steps as $i => $s): ?>
            <div>
                <div class="w-9 h-9 rounded-full bg-ink text-white flex items-center justify-center font-display font-semibold text-sm mb-3"><?= $i + 1 ?></div>
                <h3 class="font-semibold mb-1"><?= $s[0] ?></h3>
                <p class="text-ink/55"><?= $s[1] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA -->
<section class="max-w-6xl mx-auto px-5 py-16">
    <div class="bg-ink rounded-2xl px-8 py-12 text-center">
        <h2 class="font-display text-3xl font-semibold text-white">Ready to retire the visitor book?</h2>
        <p class="text-white/60 mt-3 max-w-md mx-auto">Set up your organisation, add your buildings and floors, and start issuing gate passes today.</p>
        <a href="<?= url('/signup/organization') ?>" class="inline-block mt-6 bg-brick text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-brick/90 transition">Register your organisation</a>
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
