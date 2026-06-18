<?php
/** views/visitor/book.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Book a Visit</h1>
    <p class="text-sm text-ink/55 mt-1">Choose where you're going and who you're visiting. Your host will need to approve before you get a gate pass.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-xl">
    <form method="POST" action="<?= url('/visitor/book') ?>" class="space-y-4">
        <?= csrfField() ?>

        <div>
            <label class="block text-sm font-medium mb-1.5">Organisation / building you're visiting</label>
            <select name="org_id" id="orgSelect" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                <option value="">-- Select organisation --</option>
                <?php foreach ($organizations as $o): ?>
                    <option value="<?= $o['id'] ?>"><?= e($o['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Who are you visiting? (host)</label>
            <select name="host_id" id="hostSelect" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm" disabled>
                <option value="">-- Select an organisation first --</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Purpose of visit</label>
            <input type="text" name="purpose" placeholder="e.g. Business meeting" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">When do you plan to visit?</label>
            <input type="datetime-local" name="scheduled_start" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Send visit request</button>
    </form>
</div>

<script>
document.getElementById('orgSelect').addEventListener('change', function () {
    var orgId = this.value;
    var hostSelect = document.getElementById('hostSelect');
    hostSelect.innerHTML = '<option value="">Loading...</option>';
    hostSelect.disabled = true;

    if (!orgId) {
        hostSelect.innerHTML = '<option value="">-- Select an organisation first --</option>';
        return;
    }

    fetch('<?= url('/visitor/hosts-by-org/') ?>' + orgId)
        .then(function (res) { return res.json(); })
        .then(function (hosts) {
            if (!hosts.length) {
                hostSelect.innerHTML = '<option value="">No hosts available for this organisation</option>';
                return;
            }
            hostSelect.innerHTML = '<option value="">-- Select host --</option>' +
                hosts.map(function (h) { return '<option value="' + h.id + '">' + h.label + '</option>'; }).join('');
            hostSelect.disabled = false;
        })
        .catch(function () {
            hostSelect.innerHTML = '<option value="">Could not load hosts. Please try again.</option>';
        });
});
</script>
