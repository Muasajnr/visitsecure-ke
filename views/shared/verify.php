<?php
/** views/shared/verify.php */
?>
<div class="min-h-screen flex flex-col items-center justify-center px-5 py-12 bg-blue-50/30">
    <a href="<?= url('/') ?>" class="flex items-center gap-2 mb-10">
        <span class="w-8 h-8 rounded-md bg-ink flex items-center justify-center text-white font-display font-bold text-sm">VS</span>
        <span class="font-display font-semibold text-[17px]">VisitSecure <span class="text-brick">KE</span></span>
    </a>

    <div class="bg-white border border-ink/10 rounded-2xl p-8 shadow-sm w-full max-w-sm text-center">
        <?php if (!$visit): ?>
            <div class="w-14 h-14 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="font-display text-xl font-semibold">Gate pass not found</h1>
            <p class="text-sm text-ink/55 mt-2 leading-relaxed">This QR code wasn't recognised. It may be invalid, or the pass may have been cancelled.</p>
            <a href="<?= url('/') ?>" class="inline-block mt-6 text-sm text-brick font-semibold hover:underline">Back to home</a>

        <?php else:
            $statusColor = match($visit['status']) {
                'approved','checked_in' => ['bg-green-50 border-green-100', 'text-green-700'],
                'rejected','expired','cancelled' => ['bg-red-50 border-red-100', 'text-red-600'],
                default => ['bg-yellow-50 border-yellow-100', 'text-yellow-700'],
            };
        ?>
            <div class="w-14 h-14 rounded-full <?= $statusColor[0] ?> border flex items-center justify-center mx-auto mb-4">
                <?php if (in_array($visit['status'], ['approved','checked_in'])): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 <?= $statusColor[1] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 <?= $statusColor[1] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/>
                    </svg>
                <?php endif; ?>
            </div>

            <h1 class="font-display text-xl font-semibold">Gate pass verified</h1>
            <div class="mt-1.5"><?= statusBadge($visit['status']) ?></div>

            <div class="mt-5 text-left border-t border-ink/10 pt-5 space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-ink/50">Organisation</span>
                    <span class="font-medium text-right"><?= e($visit['org_name'] ?? '—') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-ink/50">Visitor</span>
                    <span class="font-medium text-right"><?= e($visit['visitor_name']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-ink/50">Host</span>
                    <span class="font-medium text-right"><?= e($visit['host_name'] ?? '—') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-ink/50">Location</span>
                    <span class="font-medium text-right"><?= e($visit['building_name'] ?? '—') ?><?= $visit['room_name'] ? ' · ' . e($visit['room_name']) : '' ?></span>
                </div>
                <?php if (!empty($visit['scheduled_at'])): ?>
                <div class="flex justify-between">
                    <span class="text-ink/50">Scheduled</span>
                    <span class="font-medium text-right"><?= formatDate($visit['scheduled_at']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <p class="text-[11px] text-ink/35 mt-5 leading-relaxed">Check-in and check-out must be processed by a gateman from their dashboard.</p>
        <?php endif; ?>
    </div>
</div>
