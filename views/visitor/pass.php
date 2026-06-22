<?php
/** views/visitor/pass.php */
?>
<div class="mb-6 no-print">
    <a href="<?= url('/visitor/visits') ?>" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        My visits
    </a>
</div>

<div class="max-w-sm mx-auto">
    <!-- Gate pass card -->
    <div class="bg-white rounded-2xl shadow-lg border border-ink/10 overflow-hidden">
        <!-- Header strip -->
        <div class="bg-ink px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-brick flex items-center justify-center text-white font-display font-bold text-[10px]">VS</span>
                <span class="text-white font-display font-semibold text-sm">Gate Pass</span>
            </div>
            <?= statusBadge($visit['status']) ?>
        </div>

        <!-- Location breadcrumb -->
        <div class="px-6 pt-4 pb-2">
            <p class="text-[11px] text-ink/40 font-mono tracking-wide leading-relaxed">
                <?= e($visit['org_name'] ?? '') ?>
                <?php if ($visit['building_name']): ?> &rarr; <?= e($visit['building_name']) ?><?php endif; ?>
                <?php if ($visit['floor_name']): ?> &rarr; <?= e($visit['floor_name']) ?><?php endif; ?>
                <?php if ($visit['room_name']): ?> &rarr; <?= e($visit['room_name']) ?><?php endif; ?>
            </p>
        </div>

        <!-- Dashed divider -->
        <div class="mx-6 border-t border-dashed border-ink/15 my-3"></div>

        <!-- QR code -->
        <div class="px-6 py-2 text-center">
            <img src="<?= e(url($visit['qr_image_path'])) ?>" alt="Gate Pass QR Code"
                class="w-44 h-44 mx-auto rounded-lg border border-ink/5">
            <p class="font-mono text-[11px] text-ink/35 mt-3 tracking-widest">
                <?= e($visit['qr_code']) ?>
            </p>
        </div>

        <div class="mx-6 border-t border-dashed border-ink/15 my-3"></div>

        <!-- Visit details -->
        <div class="px-6 pb-6 space-y-2.5 text-sm">
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Visitor</span>
                <span class="font-medium text-right"><?= e($visit['visitor_name']) ?></span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Host</span>
                <span class="font-medium text-right"><?= e($visit['host_name'] ?? '—') ?></span>
            </div>
            <?php if ($visit['purpose']): ?>
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Purpose</span>
                <span class="font-medium text-right max-w-[55%]"><?= e($visit['purpose']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($visit['scheduled_start']): ?>
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Scheduled</span>
                <span class="font-medium text-right"><?= formatDate($visit['scheduled_start']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($visit['checked_in_at']): ?>
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Checked in</span>
                <span class="text-green-700 font-medium text-right"><?= formatDate($visit['checked_in_at'], 'h:i A') ?></span>
            </div>
            <?php endif; ?>
            <?php if ($visit['checked_out_at']): ?>
            <div class="flex justify-between items-start">
                <span class="text-ink/50">Checked out</span>
                <span class="text-ink/60 font-medium text-right"><?= formatDate($visit['checked_out_at'], 'h:i A') ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4 space-y-2 no-print">
        <button onclick="window.print()"
            class="w-full bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">
            Print / Save gate pass
        </button>
        <p class="text-center text-xs text-ink/35">Show this QR code to the gateman at the entrance.</p>
    </div>
</div>
