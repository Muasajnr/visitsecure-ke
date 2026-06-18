<?php
/** views/eventmanager/create.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Schedule an Event</h1>
    <p class="text-sm text-ink/55 mt-1">E.g. a conference in CMS Africa Building, 1st Floor, Conference 6.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <form method="POST" action="<?= url('/events/create') ?>" class="space-y-4">
        <?= csrfField() ?>

        <div>
            <label class="block text-sm font-medium mb-1.5">Event title</label>
            <input type="text" name="title" required placeholder="e.g. Q3 Investor Conference" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Description (optional)</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm"></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Building</label>
                <select name="building_id" id="buildingSelect" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <option value="">-- Select --</option>
                    <?php foreach ($buildings as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Floor</label>
                <select name="floor_id" id="floorSelect" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <option value="">-- Any / Select --</option>
                    <?php foreach ($floors as $f): ?>
                        <option value="<?= $f['id'] ?>" data-building="<?= $f['building_id'] ?>" class="floor-option" style="display:none;"><?= e($f['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Room (e.g. Conference 6)</label>
                <select name="room_id" id="roomSelect" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <option value="">-- Any / Select --</option>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= $r['id'] ?>" data-floor="<?= $r['floor_id'] ?>" class="room-option" style="display:none;"><?= e($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Start date &amp; time</label>
                <input type="datetime-local" name="start_datetime" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">End date &amp; time</label>
                <input type="datetime-local" name="end_datetime" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Maximum visitors (optional)</label>
            <input type="number" name="max_visitors" min="1" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Schedule event</button>
    </form>
</div>

<script>
function filterOptions(parentSelectId, optionClass, dataAttr, childSelectId) {
    var parentVal = document.getElementById(parentSelectId).value;
    document.querySelectorAll('.' + optionClass).forEach(function (opt) {
        var show = opt.dataset[dataAttr] === parentVal;
        opt.style.display = show ? '' : 'none';
        if (!show && opt.selected) opt.selected = false;
    });
}
document.getElementById('buildingSelect').addEventListener('change', function () {
    filterOptions('buildingSelect', 'floor-option', 'building', 'floorSelect');
    document.querySelectorAll('.room-option').forEach(function (opt) { opt.style.display = 'none'; opt.selected = false; });
});
document.getElementById('floorSelect').addEventListener('change', function () {
    filterOptions('floorSelect', 'room-option', 'floor', 'roomSelect');
});
</script>
