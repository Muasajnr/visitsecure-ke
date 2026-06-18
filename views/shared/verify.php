<?php
/** views/shared/verify.php */
?>
<div class="min-h-screen flex items-center justify-center px-5 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold">VS</span>
                <span class="font-display font-semibold text-lg">VisitSecure <span class="text-brick">KE</span></span>
            </a>
        </div>

        <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm text-center">
            <?php if (!$visit): ?>
                <span class="text-3xl">&#10060;</span>
                <h1 class="font-display text-xl font-semibold mt-3">Invalid gate pass</h1>
                <p class="text-sm text-ink/55 mt-2">This QR code was not recognised by the system.</p>
            <?php else: ?>
                <span class="text-3xl">&#9989;</span>
                <h1 class="font-display text-xl font-semibold mt-3">Gate pass found</h1>
                <div class="mt-4"><?= statusBadge($visit['status']) ?></div>
                <div class="text-left mt-6 text-sm space-y-1.5 border-t border-ink/10 pt-5">
                    <p><strong>Organisation:</strong> <?= e($visit['org_name'] ?? '-') ?></p>
                    <p><strong>Visitor:</strong> <?= e($visit['visitor_name']) ?></p>
                    <p><strong>Host:</strong> <?= e($visit['host_name'] ?? '-') ?></p>
                    <p><strong>Location:</strong> <?= e($visit['building_name'] ?? '-') ?><?= $visit['room_name'] ? ' · ' . e($visit['room_name']) : '' ?></p>
                </div>
                <p class="text-xs text-ink/40 mt-5">Check-in and check-out must be performed by a gateman from the staff dashboard.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
