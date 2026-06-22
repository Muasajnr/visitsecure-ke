<?php
/** views/orgadmin/reports.php */
$maxMonthly  = max(array_column($monthlyTrend,   'total') ?: [1]);
$maxBuilding = max(array_column($byBuilding,      'total') ?: [1]);
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Analytics</h1>
    <p class="text-sm text-ink/55 mt-1">Visit trends, status breakdowns, and top hosts across your organisation.</p>
</div>

<div class="grid md:grid-cols-2 gap-5 mb-5">
    <!-- Monthly trend -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">Monthly visits <span class="text-ink/35 font-normal text-sm">(last 6 months)</span></h2>
        <?php if (empty($monthlyTrend)): ?>
            <p class="text-sm text-ink/35 text-center py-10">No visit data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($monthlyTrend as $row): ?>
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-ink/60 font-medium"><?= e($row['ym']) ?></span>
                            <span class="font-semibold tabular-nums"><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-brick rounded-full transition-all" style="width: <?= max(2, round($row['total'] / $maxMonthly * 100)) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Status breakdown -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">By status</h2>
        <?php if (empty($statusBreakdown)): ?>
            <p class="text-sm text-ink/35 text-center py-10">No visit data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($statusBreakdown as $row): ?>
                    <div class="flex items-center justify-between">
                        <?= statusBadge($row['status']) ?>
                        <span class="font-display font-semibold text-lg"><?= $row['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-5">
    <!-- By building -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">By building</h2>
        <?php if (empty($byBuilding)): ?>
            <p class="text-sm text-ink/35 text-center py-10">No buildings set up yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($byBuilding as $row): ?>
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-ink/60 font-medium"><?= e($row['name']) ?></span>
                            <span class="font-semibold tabular-nums"><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-ink rounded-full transition-all" style="width: <?= max(2, round($row['total'] / $maxBuilding * 100)) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Top hosts -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">Most-visited hosts</h2>
        <?php if (empty($topHosts)): ?>
            <p class="text-sm text-ink/35 text-center py-10">No data yet.</p>
        <?php else: ?>
            <ol class="space-y-3">
                <?php foreach ($topHosts as $i => $h): ?>
                    <li class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full <?= $i === 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-ink/5 text-ink/40' ?> text-xs font-bold flex items-center justify-center shrink-0"><?= $i + 1 ?></span>
                        <span class="flex-1 font-medium text-sm truncate"><?= e($h['full_name']) ?></span>
                        <span class="text-sm text-ink/50 tabular-nums"><?= $h['total'] ?> visit<?= $h['total'] == 1 ? '' : 's' ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</div>
