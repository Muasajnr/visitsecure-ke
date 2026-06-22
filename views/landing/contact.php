<?php $pageTitle = 'Contact'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<section class="max-w-3xl mx-auto px-5 py-16">
    <p class="text-xs uppercase tracking-widest text-ink/40 font-semibold mb-4">Get in touch</p>
    <h1 class="font-display text-4xl font-semibold leading-snug">We'll help you get set up.</h1>
    <p class="text-ink/60 mt-5 leading-relaxed text-[17px] max-w-2xl">
        Whether you manage a single-floor office or a tower with 30 tenants, we'll walk you through the setup — buildings, floors, rooms, staff roles, the lot. Usually takes less than an hour.
    </p>

    <div class="mt-10 grid md:grid-cols-2 gap-4">
        <div class="p-6 rounded-xl border border-ink/10 bg-white">
            <p class="text-xs uppercase tracking-wide text-ink/40 font-semibold mb-2">Email us</p>
            <a href="mailto:support@visitsecure.ke" class="font-medium text-brick hover:underline">support@visitsecure.ke</a>
            <p class="text-xs text-ink/45 mt-2">We reply same-day on weekdays, usually within a few hours.</p>
        </div>
        <div class="p-6 rounded-xl border border-ink/10 bg-white">
            <p class="text-xs uppercase tracking-wide text-ink/40 font-semibold mb-2">Call or WhatsApp</p>
            <a href="tel:+254700000000" class="font-medium text-brick hover:underline">+254 700 000 000</a>
            <p class="text-xs text-ink/45 mt-2">Mon–Fri, 8 AM – 6 PM EAT.</p>
        </div>
    </div>

    <div class="mt-6 p-6 rounded-xl border border-ink/10 bg-white">
        <p class="font-display font-semibold text-sm mb-1">What to expect when you reach out</p>
        <ul class="text-sm text-ink/60 space-y-1.5 mt-3 leading-relaxed">
            <li class="flex items-start gap-2"><?= icon('check', 'w-4 h-4 text-green-600 mt-0.5 shrink-0') ?> A quick call or email exchange to understand your building setup</li>
            <li class="flex items-start gap-2"><?= icon('check', 'w-4 h-4 text-green-600 mt-0.5 shrink-0') ?> Your organisation activated and configured on the platform</li>
            <li class="flex items-start gap-2"><?= icon('check', 'w-4 h-4 text-green-600 mt-0.5 shrink-0') ?> Your first staff accounts created and explained</li>
            <li class="flex items-start gap-2"><?= icon('check', 'w-4 h-4 text-green-600 mt-0.5 shrink-0') ?> A walkthrough of the gateman, host, and admin dashboards</li>
        </ul>
    </div>

    <div class="mt-8 pt-8 border-t border-ink/10 text-sm text-ink/55">
        Already have an account? <a href="<?= url('/login') ?>" class="text-brick font-semibold">Sign in</a>, or <a href="<?= url('/signup/organization') ?>" class="text-brick font-semibold">register your organisation directly</a> if you're ready to go.
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
