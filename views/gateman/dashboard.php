<?php
/** views/gateman/dashboard.php */
$hour = (int)date('H');
$shift = $hour < 12 ? 'Morning' : ($hour < 17 ? 'Afternoon' : 'Evening');
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold"><?= $shift ?> shift</h1>
    <p class="text-sm text-ink/55 mt-1">Gate dashboard — <?= date('l, d M Y') ?>.</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Today</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['totalToday'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Inside now</p>
        <p class="font-display text-3xl font-semibold mt-1 text-green-700"><?= $visitStats['checkedIn'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Awaiting OK</p>
        <p class="font-display text-3xl font-semibold mt-1 text-yellow-600"><?= $visitStats['pending'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5 stat-card">
        <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">This month</p>
        <p class="font-display text-3xl font-semibold mt-1"><?= $visitStats['thisMonth'] ?></p>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-4 mb-7">
    <a href="<?= url('/gateman/scan') ?>" class="bg-ink text-white rounded-xl p-6 hover:bg-brick transition-colors flex items-center gap-5 group">
        <span class="shrink-0 text-white/90"><?= icon('camera', 'w-10 h-10') ?></span>
        <div>
            <p class="font-display font-semibold text-lg leading-snug">Scan gate pass</p>
            <p class="text-white/55 text-sm mt-0.5">Check in a visitor by scanning their QR code or typing the token.</p>
        </div>
    </a>
    <a href="<?= url('/gateman/walkin') ?>" class="bg-white border border-ink/10 rounded-xl p-6 hover:border-brick/30 hover:shadow-sm transition flex items-center gap-5">
        <span class="shrink-0 text-ink/40"><?= icon('pencil', 'w-10 h-10') ?></span>
        <div>
            <p class="font-display font-semibold text-lg leading-snug">Register walk-in</p>
            <p class="text-ink/55 text-sm mt-0.5">No gate pass? Register them here — host gets notified for approval.</p>
        </div>
    </a>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
        <div>
            <h2 class="font-display font-semibold">Currently inside</h2>
            <p class="text-xs text-ink/40 mt-0.5"><?= $visitStats['checkedIn'] ?> visitor<?= $visitStats['checkedIn'] == 1 ? '' : 's' ?> in the building right now</p>
        </div>
        <a href="<?= url('/gateman/log') ?>" class="text-sm text-brick font-semibold hover:underline">Full gate log</a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-ink/45 text-xs uppercase tracking-wide bg-ink/[0.02]">
                <th class="px-5 py-3 font-semibold">Visitor</th>
                <th class="px-5 py-3 font-semibold">Host / Room</th>
                <th class="px-5 py-3 font-semibold">Checked in</th>
                <th class="px-5 py-3 font-semibold"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
            <?php if (empty($currentlyIn)): ?>
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-ink/35 text-sm">
                        Nobody checked in yet — building is clear.
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($currentlyIn as $v): ?>
            <tr>
                <td class="px-5 py-3 font-medium"><?= e($v['visitor_name']) ?></td>
                <td class="px-5 py-3 text-ink/60">
                    <?= e($v['host_name'] ?? '—') ?>
                    <?php if ($v['room_name']): ?><br><span class="text-ink/40 text-xs"><?= e($v['building_name'] ?? '') ?> · <?= e($v['room_name']) ?></span><?php endif; ?>
                </td>
                <td class="px-5 py-3 text-ink/50 tabular-nums"><?= formatDate($v['checked_in_at'], 'h:i A') ?></td>
                <td class="px-5 py-3 text-right">
                    <form method="POST" action="<?= url('/gateman/checkout/' . $v['id']) ?>" data-visitor-name="<?= e($v['visitor_name']) ?>" onsubmit="return openCheckoutConfirmation(this)">
                        <?= csrfField() ?>
                        <input type="hidden" name="checkout_confirmation" value="">
                        <button type="submit" class="text-brick font-semibold text-xs hover:underline">Check out</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="checkout-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-ink/40 px-4" role="dialog" aria-modal="true" aria-labelledby="checkout-modal-title">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
        <h2 id="checkout-modal-title" class="font-display text-xl font-semibold">Confirm checkout</h2>
        <p class="mt-2 text-sm text-ink/60">You are checking out:</p>
        <p id="checkout-visitor-name" class="mt-1 font-semibold text-ink"></p>
        <label for="checkout-name-input" class="mt-5 block text-sm font-medium text-ink/75">Type the visitor's full name to continue</label>
        <input type="text" id="checkout-name-input" autocomplete="off" class="mt-1.5 w-full rounded-lg border border-ink/15 px-4 py-2.5 text-sm focus:border-blue-300 focus:ring-2 focus:ring-blue-100" aria-describedby="checkout-name-error">
        <p id="checkout-name-error" class="mt-2 hidden text-sm text-red-700">The name does not match. Please type it exactly as shown.</p>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" onclick="closeCheckoutConfirmation()" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm font-semibold text-ink/70 hover:bg-ink/5">Cancel</button>
            <button type="button" onclick="submitCheckoutConfirmation()" class="rounded-lg bg-brick px-4 py-2.5 text-sm font-semibold text-white hover:bg-ink">Confirm checkout</button>
        </div>
    </div>
</div>
