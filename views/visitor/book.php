<?php
/** views/visitor/book.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Book a visit</h1>
    <p class="text-sm text-ink/55 mt-1 max-w-md">Pick where you're going and who you're visiting. Once your host approves, you'll get a QR gate pass by email.</p>
</div>

<?php if ($error = getFlash('error')): ?>
    <div class="mb-5 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm"><?= e($error) ?></div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-xl">
    <form method="POST" action="<?= url('/visitor/book') ?>" class="space-y-4">
        <?= csrfField() ?>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Which organisation are you visiting?</label>
            <select name="org_id" id="orgSelect" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                <option value="">— Select organisation —</option>
                <?php foreach ($organizations as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= old('org_id') == $o['id'] ? 'selected' : '' ?>><?= e($o['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Who are you visiting?</label>
            <select name="host_id" id="hostSelect" required disabled
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition bg-ink/[0.02]">
                <option value="">— Select an organisation first —</option>
            </select>
            <p id="hostHint" class="text-[11px] text-ink/40 mt-1 hidden">Select the person you have an appointment with.</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">Purpose of visit <span class="text-ink/35 font-normal">(optional)</span></label>
            <input type="text" name="purpose" value="<?= old('purpose') ?>" placeholder="e.g. Business meeting, Interview, Delivery"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-ink/75">When do you plan to visit?</label>
            <input type="datetime-local" name="scheduled_start" required value="<?= old('scheduled_start') ?>"
                class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
            <p class="text-[11px] text-ink/40 mt-1">Your host will see this when they get the approval request.</p>
        </div>

        <div class="pt-1">
            <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">
                Send visit request
            </button>
            <p class="text-xs text-ink/40 mt-2">You'll get an email once the host approves — with your gate pass attached.</p>
        </div>
    </form>
</div>

<script>
(function () {
    var orgSelect  = document.getElementById('orgSelect');
    var hostSelect = document.getElementById('hostSelect');
    var hostHint   = document.getElementById('hostHint');

    orgSelect.addEventListener('change', function () {
        var orgId = this.value;
        hostSelect.innerHTML = '<option value="">Loading hosts…</option>';
        hostSelect.disabled = true;
        hostHint.classList.add('hidden');

        if (!orgId) {
            hostSelect.innerHTML = '<option value="">— Select an organisation first —</option>';
            hostSelect.classList.add('bg-ink/[0.02]');
            return;
        }

        fetch('<?= url('/visitor/hosts-by-org/') ?>' + orgId)
            .then(function (r) { return r.json(); })
            .then(function (hosts) {
                if (!hosts.length) {
                    hostSelect.innerHTML = '<option value="">No hosts available for this organisation</option>';
                    return;
                }
                hostSelect.innerHTML = '<option value="">— Select host —</option>' +
                    hosts.map(function (h) {
                        return '<option value="' + h.id + '">' + h.label + '</option>';
                    }).join('');
                hostSelect.disabled = false;
                hostSelect.classList.remove('bg-ink/[0.02]');
                hostHint.classList.remove('hidden');
            })
            .catch(function () {
                hostSelect.innerHTML = '<option value="">Could not load hosts — try refreshing.</option>';
            });
    });
})();
</script>
