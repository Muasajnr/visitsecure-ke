<?php
/** views/gateman/walkin.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Register walk-in visitor</h1>
    <p class="text-sm text-ink/55 mt-1 max-w-xl">For visitors who arrive without a gate pass. Fill in their details, pick the host, and the host will get a notification to approve or reject. The visitor waits until you get the OK.</p>
</div>

<?php if ($flash = getFlash('success')): ?>
    <div class="mb-5 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-800 text-sm"><?= e($flash) ?></div>
<?php endif; ?>
<?php if ($flash = getFlash('error')): ?>
    <div class="mb-5 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($flash) ?></div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <form method="POST" action="<?= url('/gateman/walkin') ?>" class="space-y-4">
        <?= csrfField() ?>

        <p class="text-xs uppercase tracking-widest text-ink/35 font-semibold">Visitor details</p>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Full name</label>
                <input type="text" name="visitor_name" required value="<?= old('visitor_name') ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Phone number</label>
                <input type="text" name="visitor_phone" required value="<?= old('visitor_phone') ?>" placeholder="07XX XXX XXX"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Email <span class="text-ink/35 font-normal">(optional)</span></label>
                <input type="email" name="visitor_email" value="<?= old('visitor_email') ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">National ID / Passport <span class="text-ink/35 font-normal">(optional)</span></label>
                <input type="text" name="visitor_id_number" value="<?= old('visitor_id_number') ?>"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Company <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="visitor_company" value="<?= old('visitor_company') ?>"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>

        <div class="border-t border-ink/10 pt-5">
            <p class="text-xs uppercase tracking-widest text-ink/35 font-semibold mb-4">Who are they here to see?</p>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Host</label>
                <select name="host_id" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                    <option value="">— Select host —</option>
                    <?php foreach ($hosts as $h): ?>
                        <option value="<?= $h['id'] ?>" <?= old('host_id') == $h['id'] ? 'selected' : '' ?>>
                            <?= e($h['full_name']) ?> — <?= e($h['building_name'] ?? '') ?><?= $h['floor_name'] ? ' · ' . e($h['floor_name']) : '' ?><?= $h['room_name'] ? ' · ' . e($h['room_name']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($hosts)): ?>
                    <p class="text-xs text-brick mt-1.5">No hosts have been configured. Ask your org admin to add them under Staff &amp; Users.</p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Purpose of visit <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="purpose" value="<?= old('purpose') ?>" placeholder="e.g. Meeting, Delivery, Interview"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>

        <div class="pt-1">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">
                Register visitor &amp; request approval
            </button>
            <p class="text-xs text-ink/40 mt-2">The host will be notified immediately. Ask the visitor to wait.</p>
        </div>
    </form>
</div>
