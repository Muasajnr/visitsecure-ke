<?php $pageTitle = 'About'; ?>
<?php require VIEWS_PATH . '/partials/public_header.php'; ?>

<section class="max-w-3xl mx-auto px-5 py-16">
    <p class="text-xs uppercase tracking-widest text-ink/40 font-semibold mb-4">Our story</p>
    <h1 class="font-display text-4xl font-semibold leading-snug">We got tired of the visitor book too.</h1>

    <p class="text-ink/65 mt-7 leading-relaxed text-[17px]">
        If you've ever walked into a multi-tenant building in Nairobi and had to write your full name, ID number, company, and who you're visiting in a dog-eared logbook — only for that information to never be looked at again — you know the problem we're solving.
    </p>
    <p class="text-ink/65 mt-5 leading-relaxed text-[17px]">
        VisitSecure KE started as a straightforward question: what would it look like if the visitor register actually worked? If it tracked check-ins and check-outs, notified hosts the moment a guest arrived, and gave gatemen a fast way to verify who belongs in the building?
    </p>
    <p class="text-ink/65 mt-5 leading-relaxed text-[17px]">
        Buildings in Kenya aren't simple. A tower like Bihi Towers might have 20 floors, 40 firms, and dozens of hosts across the whole building. Generic visitor management tools — built for a single-tenant office with one reception desk — fall apart here. So we designed around that reality from the beginning: building → floor → room → host, with every visit scoped to the right location.
    </p>
    <p class="text-ink/65 mt-5 leading-relaxed text-[17px]">
        The result is a platform where every organisation manages its own staff and visitors, gatemen have a fast scan-based workflow, and hosts get notified without anyone needing to make a phone call. No paper. No guessing. Just a proper digital trail.
    </p>

    <div class="mt-12 pt-10 border-t border-ink/10 grid md:grid-cols-3 gap-6 text-sm">
        <div class="p-5 rounded-xl bg-blue-50/60 border border-blue-100">
            <p class="font-display font-semibold text-ink">Multi-tenant by design</p>
            <p class="text-ink/60 mt-2 leading-relaxed">Each organisation's data is fully isolated. You see your buildings, your visits, your staff — nothing else.</p>
        </div>
        <div class="p-5 rounded-xl bg-blue-50/60 border border-blue-100">
            <p class="font-display font-semibold text-ink">Floor-level accuracy</p>
            <p class="text-ink/60 mt-2 leading-relaxed">Visits aren't just "at the building." They're tied to a specific floor, room, and host — so your gateman always knows where to send someone.</p>
        </div>
        <div class="p-5 rounded-xl bg-blue-50/60 border border-blue-100">
            <p class="font-display font-semibold text-ink">No apps required</p>
            <p class="text-ink/60 mt-2 leading-relaxed">QR codes go out by email. Any device with a camera can scan them at the gate. Your visitors don't have to install anything.</p>
        </div>
    </div>

    <div class="mt-12 flex gap-4 flex-wrap">
        <a href="<?= url('/signup/organization') ?>" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Register your organisation</a>
        <a href="<?= url('/contact') ?>" class="border border-ink/20 px-5 py-2.5 rounded-lg font-semibold text-sm hover:border-ink/40 transition">Talk to us first</a>
    </div>
</section>

<?php require VIEWS_PATH . '/partials/public_footer.php'; ?>
