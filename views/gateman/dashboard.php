<?php
/** views/gateman/dashboard.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Gate Dashboard</h1>
    <p class="text-sm text-ink/55 mt-1">Quick overview and fast actions for the gate.</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Visits today</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['totalToday'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Currently in</p>
        <p class="font-display text-3xl font-semibold mt-1 text-verigreen"><?= $visitStats['checkedIn'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Pending approval</p>
        <p class="font-display text-3xl font-semibold mt-1 text-yellow-600"><?= $visitStats['pending'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">This month</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['thisMonth'] ?></p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-4 mb-8">
    <a href="<?= url('/gateman/scan') ?>" class="bg-ink text-white rounded-xl p-6 hover:bg-brick transition flex items-center gap-4">
        <span class="text-3xl">&#128247;</span>
        <div>
            <p class="font-display font-semibold text-lg">Scan QR Gate Pass</p>
            <p class="text-white/60 text-sm">Check in a registered visitor by scanning or entering their token.</p>
        </div>
    </a>
    <a href="<?= url('/gateman/walkin') ?>" class="bg-white border border-ink/10 rounded-xl p-6 hover:border-brick/30 transition flex items-center gap-4">
        <span class="text-3xl">&#128221;</span>
        <div>
            <p class="font-display font-semibold text-lg">Register Walk-in Visitor</p>
            <p class="text-ink/55 text-sm">No gate pass? Register them here and request host approval.</p>
        </div>
    </a>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <h2 class="font-display font-semibold">Currently inside the building</h2>
        <a href="<?= url('/gateman/log') ?>" class="text-sm text-brick font-semibold">Full gate log</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10">
                <th class="px-5 py-3">Visitor</th>
                <th class="px-5 py-3">Host</th>
                <th class="px-5 py-3">Location</th>
                <th class="px-5 py-3">Checked in</th>
                <th class="px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($currentlyIn)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No visitors currently inside.</td></tr>
            <?php endif; ?>
            <?php foreach ($currentlyIn as $v): ?>
            <tr class="border-b border-ink/5 last:border-0">
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['host_name'] ?? '-') ?></td>
                <td class="px-5 py-3 text-ink/60"><?= e($v['building_name'] ?? '-') ?><?= $v['room_name'] ? ' · ' . e($v['room_name']) : '' ?></td>
                <td class="px-5 py-3 text-ink/50"><?= formatDate($v['checked_in_at'], 'h:i A') ?></td>
                <td class="px-5 py-3">
                    <form method="POST" action="<?= url('/gateman/checkout/' . $v['id']) ?>" onsubmit="return confirmAction('Check this visitor out?')">
                        <?= csrfField() ?>
                        <button type="submit" class="text-brick font-semibold text-xs">Check out</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
