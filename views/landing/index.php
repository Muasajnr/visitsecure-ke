<?php $pageTitle = 'Home'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<!-- HERO -->
<section class="max-w-6xl mx-auto px-5 pt-14 pb-20 grid md:grid-cols-2 gap-16 items-center">
    <div>
        <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold tracking-wide uppercase mb-6 border border-blue-100">Nairobi-built visitor management</span>
        <h1 class="font-display text-4xl md:text-5xl font-semibold leading-tight tracking-tight">
            The visitor book<br>stops here.
        </h1>
        <p class="mt-5 text-ink/60 text-lg leading-relaxed max-w-md">
            No more loose sheets, scribbled names, or arguing about who signed in. VisitSecure KE gives every visitor a proper QR gate pass — tied to the right host, the right room, the right time.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="<?= url('/signup/organization') ?>" class="bg-brick text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-brick/90 transition">Set up your organisation</a>
            <a href="<?= url('/signup/visitor') ?>" class="border border-ink/20 px-6 py-3 rounded-lg font-semibold text-sm hover:border-ink/40 hover:bg-white transition">I'm visiting someone</a>
        </div>
        <div class="mt-10 flex items-start gap-6 text-sm text-ink/50">
            <div>
                <span class="block font-display text-xl font-semibold text-ink">Works per floor</span>
                building → floor → room → host
            </div>
            <div class="w-px h-10 bg-ink/10 mt-0.5"></div>
            <div>
                <span class="block font-display text-xl font-semibold text-ink">Multi-tenant</span>
                many firms, one platform
            </div>
        </div>
    </div>

    <!-- Gate pass mockup -->
    <div class="relative">
        <div class="absolute -inset-3 bg-blue-50 rounded-3xl rotate-1 border border-blue-100/60"></div>
        <div class="relative bg-white rounded-2xl shadow-lg border border-ink/10 p-6 max-w-sm mx-auto">
            <div class="flex items-center justify-between mb-4">
                <span class="font-display font-semibold text-sm tracking-tight">Gate Pass</span>
                <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 text-xs font-semibold border border-green-100">Approved</span>
            </div>
            <div class="text-xs text-ink/40 font-mono mb-1">Bihi Towers &rarr; Floor 6 &rarr; Conference 6</div>
            <div class="border-t border-dashed border-ink/15 my-4"></div>
            <div class="flex gap-4 items-start">
                <div class="w-20 h-20 bg-ink/5 rounded-md grid grid-cols-5 grid-rows-5 gap-px p-1.5 shrink-0">
                    <?php
                    $pattern = [1,0,1,1,0, 0,1,1,0,1, 1,1,0,1,1, 0,0,1,0,1, 1,1,0,1,0];
                    foreach ($pattern as $b): ?>
                        <div class="<?= $b ? 'bg-ink' : 'bg-transparent' ?> rounded-sm"></div>
                    <?php endforeach; ?>
                </div>
                <div class="text-sm">
                    <p class="font-semibold">Achieng Otieno</p>
                    <p class="text-ink/50 text-xs mt-0.5">Visiting: Wanjiru Kamau</p>
                    <p class="text-ink/50 text-xs">Cap Africa Consulting</p>
                    <p class="text-ink/40 text-xs mt-1">Thu 19 Jun · 10:30 AM</p>
                    <p class="font-mono text-[10px] text-ink/35 mt-2 tracking-wider">TOKEN A1F9-22DC-77BE</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="max-w-6xl mx-auto px-5 py-16 border-t border-ink/10">
    <div class="max-w-xl mb-12">
        <h2 class="font-display text-3xl font-semibold">Built for how buildings actually work</h2>
        <p class="text-ink/60 mt-3 leading-relaxed">If your building has multiple firms on the same floor, or multiple floors in the same tower, you already know why generic visitor apps fall short. This one doesn't.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-5">
        <?php
        $features = [
            ['building', 'Exact location, every time', 'Map your property down to the room: building → floor → room or firm → host. The gateman always knows exactly where a visitor is headed.'],
            ['check-circle', 'QR check-in that actually works', 'Every approved visit gets a unique gate pass. The gateman scans it at the door — no clipboard, no phone calls to reception.'],
            ['pencil', 'Unplanned visitors handled', 'Walk-in guest? The gateman registers them on the spot, the host gets pinged, and everything is logged — no paper trail needed.'],
            ['calendar', 'Events with room-level booking', 'Schedule a conference in Room 3B, share a link, and let visitors register themselves or be added manually.'],
            ['envelope', 'Email alerts that go out fast', 'Hosts and visitors get notified the moment a pass is issued, approved, or rejected — via Gmail SMTP and in-app.'],
            ['chart-bar', 'Traffic reports worth looking at', 'See which floors get the most visitors, when the peak hours are, and how visit numbers compare week to week.'],
        ];
        foreach ($features as $f): ?>
            <div class="p-6 rounded-xl border border-ink/10 bg-white hover:border-blue-200 hover:shadow-sm transition-all">
                <span class="text-brick"><?= icon($f[0], 'w-7 h-7') ?></span>
                <h3 class="font-display font-semibold mt-3 text-[15px] leading-snug"><?= $f[1] ?></h3>
                <p class="text-sm text-ink/55 mt-2 leading-relaxed"><?= $f[2] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="max-w-6xl mx-auto px-5 py-16 border-t border-ink/10">
    <h2 class="font-display text-3xl font-semibold mb-3">A visit, step by step</h2>
    <p class="text-ink/55 mb-10 max-w-lg">From booking to check-out, every step is logged. Nothing falls through.</p>
    <div class="grid md:grid-cols-4 gap-6 text-sm relative">
        <?php
        $steps = [
            ['Book', 'A visitor, host, or event manager creates a visit — picking the exact building, floor, room, and time.'],
            ['Approve', 'The host gets an email and an in-app notification. One click to approve or decline.'],
            ['Scan in', 'The visitor shows their QR gate pass at the entrance. The gateman scans it — done in seconds.'],
            ['Sign out', 'When the visitor leaves, the gateman checks them out. The full audit trail is saved automatically.'],
        ];
        foreach ($steps as $i => $s): ?>
            <div class="relative">
                <div class="w-8 h-8 rounded-full bg-ink text-white flex items-center justify-center font-display font-semibold text-sm mb-3"><?= $i + 1 ?></div>
                <h3 class="font-semibold mb-1.5"><?= $s[0] ?></h3>
                <p class="text-ink/55 leading-relaxed"><?= $s[1] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- TRUST BAR -->
<section class="max-w-6xl mx-auto px-5 py-12 border-t border-ink/10">
    <div class="grid md:grid-cols-3 gap-6 text-sm text-ink/60">
        <div class="flex gap-3 items-start">
            <span class="text-brick mt-0.5 shrink-0"><?= icon('lock', 'w-6 h-6') ?></span>
            <div>
                <p class="font-semibold text-ink">Data stays with your organisation</p>
                <p class="mt-1">Your visitor logs are yours. Each organisation's data is isolated — the system is multi-tenant by design.</p>
            </div>
        </div>
        <div class="flex gap-3 items-start">
            <span class="text-brick mt-0.5 shrink-0"><?= icon('globe', 'w-6 h-6') ?></span>
            <div>
                <p class="font-semibold text-ink">No app install required</p>
                <p class="mt-1">Visitors receive a QR code by email. Gatemen use any device with a browser. Nothing to download.</p>
            </div>
        </div>
        <div class="flex gap-3 items-start">
            <span class="text-brick mt-0.5 shrink-0"><?= icon('map-pin', 'w-6 h-6') ?></span>
            <div>
                <p class="font-semibold text-ink">Built for Kenya</p>
                <p class="mt-1">Priced in KES. Tested against the kind of busy multi-firm buildings you'll find in Nairobi's CBD.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="max-w-6xl mx-auto px-5 pb-20">
    <div class="bg-ink rounded-2xl px-8 py-14 flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <h2 class="font-display text-3xl font-semibold text-white leading-snug">Ready to bin the visitor book?</h2>
            <p class="text-white/55 mt-3 max-w-md leading-relaxed">Register your organisation, add your buildings and floors, and your first gate pass can go out the same day.</p>
        </div>
        <div class="shrink-0 flex flex-col gap-3 items-start md:items-end">
            <a href="<?= url('/signup/organization') ?>" class="inline-block bg-brick text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-brick/90 transition whitespace-nowrap">Register your organisation</a>
            <a href="<?= url('/contact') ?>" class="text-white/55 text-sm hover:text-white transition">Have questions first? Get in touch &rarr;</a>
        </div>
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
