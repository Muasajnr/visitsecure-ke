<?php
/** views/gateman/walkin.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Register Walk-in Visitor</h1>
    <p class="text-sm text-ink/55 mt-1">For visitors with no gate pass. The host will be notified to approve before entry.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <form method="POST" action="<?= url('/gateman/walkin') ?>" class="space-y-4">
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
                <label class="block text-sm font-medium mb-1.5">Email (optional)</label>
                <input type="email" name="visitor_email" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">National ID (optional)</label>
                <input type="text" name="visitor_id_number" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Company (optional)</label>
            <input type="text" name="visitor_company" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <div class="border-t border-ink/10 pt-4">
            <label class="block text-sm font-medium mb-1.5">Who are they here to see? (host)</label>
            <select name="host_id" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                <option value="">-- Select host --</option>
                <?php foreach ($hosts as $h): ?>
                    <option value="<?= $h['id'] ?>">
                        <?= e($h['full_name']) ?> &mdash; <?= e($h['building_name'] ?? '') ?> · <?= e($h['floor_name'] ?? '') ?> · <?= e($h['room_name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($hosts)): ?>
                <p class="text-xs text-brick mt-2">No hosts have been added yet. Ask your organisation admin to add hosts under Staff &amp; Users.</p>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Purpose of visit</label>
            <input type="text" name="purpose" placeholder="e.g. Meeting, Delivery, Interview" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Register visitor &amp; request approval</button>
    </form>
</div>
