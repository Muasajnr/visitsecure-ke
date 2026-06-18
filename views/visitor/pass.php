<?php
/** views/visitor/pass.php */
?>
<div class="mb-6 no-print">
    <a href="<?= url('/visitor/visits') ?>" class="text-sm text-ink/50 hover:text-ink">&larr; My visits</a>
</div>

<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-ink/10 p-7">
        <div class="flex items-center justify-between mb-5">
            <span class="font-display font-semibold">Gate Pass</span>
            <?= statusBadge($visit['status']) ?>
        </div>

        <p class="text-xs text-ink/40 font-mono tracking-wide mb-1">
            <?= e($visit['org_name'] ?? '') ?> &rarr; <?= e($visit['building_name'] ?? '') ?><?= $visit['floor_name'] ? ' &rarr; ' . e($visit['floor_name']) : '' ?><?= $visit['room_name'] ? ' &rarr; ' . e($visit['room_name']) : '' ?>
        </p>

        <div class="border-t border-dashed border-ink/15 my-4"></div>

        <div class="text-center">
            <img src="<?= e(url($visit['qr_image_path'])) ?>" alt="Gate Pass QR Code" class="w-48 h-48 mx-auto">
            <p class="font-mono text-xs text-ink/40 mt-3 tracking-wider">TOKEN: <?= e($visit['qr_code']) ?></p>
        </div>

        <div class="border-t border-dashed border-ink/15 my-4"></div>

        <div class="text-sm space-y-1">
            <p><strong>Visitor:</strong> <?= e($visit['visitor_name']) ?></p>
            <p><strong>Host:</strong> <?= e($visit['host_name'] ?? '-') ?></p>
            <?php if ($visit['purpose']): ?><p><strong>Purpose:</strong> <?= e($visit['purpose']) ?></p><?php endif; ?>
            <?php if ($visit['scheduled_start']): ?><p><strong>Scheduled:</strong> <?= formatDate($visit['scheduled_start']) ?></p><?php endif; ?>
        </div>
    </div>

    <button onclick="window.print()" class="no-print w-full mt-4 bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">Print / Save gate pass</button>
</div>
