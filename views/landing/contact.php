<?php $pageTitle = 'Contact'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<section class="max-w-3xl mx-auto px-5 py-16">
    <h1 class="font-display text-4xl font-semibold">Get in touch</h1>
    <p class="text-ink/60 mt-4 leading-relaxed">
        Want to bring VisitSecure KE to your building or organisation? Reach out and our team will help you get set up, including registering your buildings, floors, and staff.
    </p>

    <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="p-6 rounded-xl border border-ink/10 bg-white">
            <p class="text-xs uppercase tracking-wide text-ink/40 font-semibold">Email</p>
            <p class="mt-1 font-medium">support@visitsecure.ke</p>
        </div>
        <div class="p-6 rounded-xl border border-ink/10 bg-white">
            <p class="text-xs uppercase tracking-wide text-ink/40 font-semibold">Phone</p>
            <p class="mt-1 font-medium">+254 700 000 000</p>
        </div>
    </div>

    <div class="mt-8 p-6 rounded-xl border border-ink/10 bg-white">
        <p class="text-sm text-ink/60">Already have an organisation account? <a href="<?= url('/login') ?>" class="text-brick font-semibold">Sign in</a> instead, or <a href="<?= url('/signup/organization') ?>" class="text-brick font-semibold">register your organisation</a> directly.</p>
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
