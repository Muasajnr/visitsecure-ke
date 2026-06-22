<?php $pageTitle = 'Pricing'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<section class="max-w-5xl mx-auto px-5 py-16">
    <div class="max-w-xl mb-4">
        <p class="text-xs uppercase tracking-widest text-ink/40 font-semibold mb-4">Pricing</p>
        <h1 class="font-display text-4xl font-semibold leading-snug">Straightforward plans.<br>No surprises.</h1>
    </div>
    <p class="text-ink/60 mt-4 mb-12 max-w-xl leading-relaxed">Subscriptions are activated by our team after onboarding — we make sure the setup actually fits your building before you go live. <a href="<?= url('/contact') ?>" class="text-brick font-semibold">Contact us</a> to get started.</p>

    <div class="grid md:grid-cols-3 gap-6">
        <?php
        $plans = [
            [
                'name' => 'Basic',
                'price' => 'KES 5,000',
                'period' => '/month',
                'desc' => 'Right for a single-building organisation with a small team.',
                'features' => ['1 building', 'Up to 5 hosts', 'QR gate passes', 'Email notifications', 'Visit history & logs'],
                'popular' => false,
            ],
            [
                'name' => 'Growth',
                'price' => 'KES 15,000',
                'period' => '/month',
                'desc' => 'For multi-floor towers or organisations with several buildings.',
                'features' => ['Multiple buildings & floors', 'Unlimited hosts', 'Event scheduling & management', 'Analytics dashboard', 'Email notifications'],
                'popular' => true,
            ],
            [
                'name' => 'Enterprise',
                'price' => 'Custom',
                'period' => '',
                'desc' => 'For property groups or large campuses managing multiple organisations.',
                'features' => ['Multiple organisations', 'Dedicated SMTP config', 'Priority support', 'Custom onboarding', 'SLA available'],
                'popular' => false,
            ],
        ];
        foreach ($plans as $p): ?>
            <div class="p-8 rounded-2xl border <?= $p['popular'] ? 'border-brick shadow-md' : 'border-ink/10' ?> bg-white relative flex flex-col">
                <?php if ($p['popular']): ?>
                    <span class="absolute -top-3 left-7 bg-brick text-white text-xs px-3 py-1 rounded-full font-semibold">Most popular</span>
                <?php endif; ?>
                <h3 class="font-display font-semibold text-lg"><?= $p['name'] ?></h3>
                <p class="mt-3">
                    <span class="font-display text-3xl font-semibold"><?= $p['price'] ?></span>
                    <span class="text-ink/50 text-sm"><?= $p['period'] ?></span>
                </p>
                <p class="text-sm text-ink/55 mt-3 leading-relaxed"><?= $p['desc'] ?></p>
                <ul class="mt-5 space-y-2 text-sm text-ink/70 flex-1">
                    <?php foreach ($p['features'] as $feat): ?>
                        <li class="flex items-start gap-2"><?= icon('check', 'w-4 h-4 text-green-600 mt-0.5 shrink-0') ?><?= $feat ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= url('/contact') ?>" class="block text-center mt-7 px-4 py-2.5 rounded-lg font-semibold text-sm transition <?= $p['popular'] ? 'bg-brick text-white hover:bg-brick/90' : 'border border-ink/20 hover:border-ink/40' ?>">
                    <?= $p['name'] === 'Enterprise' ? 'Let\'s talk' : 'Get started' ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-12 p-6 rounded-xl bg-blue-50/60 border border-blue-100 text-sm text-ink/65 leading-relaxed max-w-2xl">
        <span class="font-semibold text-ink">Not sure which plan fits?</span> Most organisations with a single building and a small team start on Basic and upgrade when they add floors or bring on event management. If your setup is unusual, just <a href="<?= url('/contact') ?>" class="text-brick font-semibold">get in touch</a> — we'll figure it out together.
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
