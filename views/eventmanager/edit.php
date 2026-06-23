<?php
/** views/eventmanager/edit.php */
$startVal = date('Y-m-d\TH:i', strtotime($event['start_datetime']));
$endVal = date('Y-m-d\TH:i', strtotime($event['end_datetime']));
?>
<div class="mb-5">
    <a href="<?= url('/events/' . $event['id']) ?>" class="text-sm text-ink/50 hover:text-ink">&larr; Back to event</a>
</div>

<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Edit event</h1>
    <p class="text-sm text-ink/55 mt-1">Update details for <?= e($event['title']) ?>.</p>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-6 max-w-2xl">
    <form method="POST" action="<?= url('/events/' . $event['id'] . '/edit') ?>" class="space-y-4">
        <?= csrfField() ?>

        <div>
            <label class="block text-sm font-medium mb-1.5">Event title</label>
            <input type="text" name="title" required value="<?= e($event['title']) ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Description (optional)</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm"><?= e($event['description'] ?? '') ?></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Building</label>
                <select name="building_id" id="buildingSelect" required class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <?php foreach ($buildings as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= (int)$event['building_id'] === (int)$b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Floor</label>
                <select name="floor_id" id="floorSelect" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <option value="">-- Any / Select --</option>
                    <?php foreach ($floors as $f): ?>
                        <option value="<?= $f['id'] ?>" data-building="<?= $f['building_id'] ?>" class="floor-option" <?= (int)($event['floor_id'] ?? 0) === (int)$f['id'] ? 'selected' : '' ?>><?= e($f['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Room</label>
                <select name="room_id" id="roomSelect" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
                    <option value="">-- Any / Select --</option>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= $r['id'] ?>" data-floor="<?= $r['floor_id'] ?>" class="room-option" <?= (int)($event['room_id'] ?? 0) === (int)$r['id'] ? 'selected' : '' ?>><?= e($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Start date &amp; time</label>
                <input type="datetime-local" name="start_datetime" required value="<?= e($startVal) ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">End date &amp; time</label>
                <input type="datetime-local" name="end_datetime" required value="<?= e($endVal) ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Maximum visitors (optional)</label>
            <input type="number" name="max_visitors" min="1" value="<?= e($event['max_visitors'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm">
        </div>

        <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Save changes</button>
    </form>
</div>

<script>
function filterOptions(parentSelectId, optionClass, dataAttr) {
    var parentVal = document.getElementById(parentSelectId).value;
    document.querySelectorAll('.' + optionClass).forEach(function (opt) {
        if (!opt.value) return;
        opt.style.display = opt.dataset[dataAttr] === parentVal ? '' : 'none';
    });
}
document.getElementById('buildingSelect').addEventListener('change', function () {
    filterOptions('buildingSelect', 'floor-option', 'building');
    document.querySelectorAll('.room-option').forEach(function (opt) {
        if (!opt.value) return;
        opt.style.display = 'none';
    });
});
document.getElementById('floorSelect').addEventListener('change', function () {
    filterOptions('floorSelect', 'room-option', 'floor');
});
filterOptions('buildingSelect', 'floor-option', 'building');
filterOptions('floorSelect', 'room-option', 'floor');
</script>
