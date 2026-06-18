<?php
/** views/eventmanager/register_visitor.php */
?>
<div class="mb-5">
    <a href="<?= url('/events/' . $event['id']) ?>" class="text-sm text-ink/50 hover:text-ink">&larr; <?= e($event['title']) ?></a>
</div>

<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Register a Visitor</h1>
    <p class="text-sm text-ink/55 mt-1">For event: <?= e($event['title']) ?></p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-lg">
    <form method="POST" action="<?= url('/events/' . $event['id'] . '/register') ?>" class="space-y-4">
        <?= csrfField() ?>
        <div>
            <label class="block text-sm font-medium mb-1.5">Visitor full name</label>
            <input type="text" name="visitor_name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Phone number</label>
            <input type="text" name="visitor_phone" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Email (for sending the gate pass)</label>
            <input type="email" name="visitor_email" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Company (optional)</label>
            <input type="text" name="visitor_company" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Register &amp; send gate pass</button>
    </form>
</div>
