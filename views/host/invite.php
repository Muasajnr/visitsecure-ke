<?php
/** views/host/invite.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Invite a Visitor</h1>
    <p class="text-sm text-ink/55 mt-1">Pre-register someone you're expecting. They'll receive a QR gate pass by email immediately.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <form method="POST" action="<?= url('/host/invite') ?>" class="space-y-4">
        <?= csrfField() ?>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Visitor full name</label>
                <input type="text" name="visitor_name" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Phone number</label>
                <input type="text" name="visitor_phone" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Email (for sending the gate pass)</label>
                <input type="email" name="visitor_email" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Company (optional)</label>
                <input type="text" name="visitor_company" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Purpose of visit</label>
            <input type="text" name="purpose" placeholder="e.g. Quarterly review meeting" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Scheduled date &amp; time</label>
                <input type="datetime-local" name="scheduled_start" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Expected end time (optional)</label>
                <input type="datetime-local" name="scheduled_end" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>

        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Generate gate pass &amp; notify visitor</button>
    </form>
</div>
