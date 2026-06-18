<?php $pageTitle = 'Pricing'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<section class="max-w-5xl mx-auto px-5 py-16">
    <div class="text-center max-w-xl mx-auto mb-12">
        <h1 class="font-display text-4xl font-semibold">Simple pricing for every building</h1>
        <p class="text-ink/60 mt-3">Subscriptions are currently activated manually by our team after setup. Contact us to get your organisation onboarded.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <?php
        $plans = [
            ['Basic', 'KES 5,000/mo', 'Single building, up to 5 hosts, email notifications included.', false],
            ['Growth', 'KES 15,000/mo', 'Multiple buildings and floors, unlimited hosts, event scheduling.', true],
            ['Enterprise', 'Custom', 'Multiple organisations, dedicated SMTP, priority support.', false],
        ];
        foreach ($plans as $p): ?>
            <div class="p-8 rounded-2xl border <?= $p[3] ? 'border-brick shadow-lg' : 'border-ink/10' ?> bg-white relative">
                <?php if ($p[3]): ?><span class="absolute -top-3 left-8 bg-brick text-white text-xs px-3 py-1 rounded-full font-semibold">Most popular</span><?php endif; ?>
                <h3 class="font-display font-semibold text-lg"><?= $p[0] ?></h3>
                <p class="font-display text-3xl font-semibold mt-3"><?= $p[1] ?></p>
                <p class="text-sm text-ink/55 mt-4 leading-relaxed"><?= $p[2] ?></p>
                <a href="<?= url('/contact') ?>" class="block text-center mt-6 px-4 py-2.5 rounded-lg font-semibold text-sm <?= $p[3] ? 'bg-brick text-white' : 'border border-ink/15' ?>">Talk to us</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
