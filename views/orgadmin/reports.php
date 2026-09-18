<?php
/** views/orgadmin/reports.php */
$maxDaily    = max(array_column($dailyTrend, 'total') ?: [1]);
$maxBuilding = max(array_column($byBuilding,      'total') ?: [1]);
?>
<div class="mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h1 class="font-display text-2xl font-semibold">Analytics &amp; reports</h1>
            <p class="text-sm text-ink/55 mt-1">Operational visitor activity for the selected reporting period.</p>
        </div>
        <a href="<?= url('/orgadmin/reports/export?from=' . urlencode($filters['from']) . '&to=' . urlencode($filters['to'])) ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-brick transition">Download CSV</a>
    </div>
</div>

<form method="GET" action="<?= url('/orgadmin/reports') ?>" class="bg-white rounded-xl border border-ink/10 p-4 mb-5 flex flex-wrap items-end gap-3">
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">From</label><input type="date" name="from" value="<?= e($filters['from']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"></div>
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">To</label><input type="date" name="to" value="<?= e($filters['to']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"></div>
    <button class="bg-brick text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-ink transition">Apply period</button>
</form>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
    <?php foreach ([['Visits', $summary['total'] ?? 0], ['Approved', $summary['approved'] ?? 0], ['Walk-ins', $summary['walk_ins'] ?? 0], ['Avg. stay', ($summary['avg_duration'] ?? 0) . ' min']] as $metric): ?>
        <div class="bg-white rounded-xl border border-ink/10 p-5"><p class="text-xs text-ink/50 uppercase tracking-wide font-semibold"><?= e($metric[0]) ?></p><p class="font-display text-2xl font-semibold mt-1"><?= e((string)$metric[1]) ?></p></div>
    <?php endforeach; ?>
</div>

<div class="grid md:grid-cols-2 gap-5 mb-5">
    <!-- Monthly trend -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">Daily visits</h2>
        <?php if (empty($dailyTrend)): ?>
            <p class="text-sm text-ink/35 text-center py-10">No visit data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($dailyTrend as $row): ?>
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-ink/60 font-medium"><?= e($row['day']) ?></span>
                            <span class="font-semibold tabular-nums"><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-brick rounded-full transition-all" style="width: <?= max(2, round($row['total'] / $maxDaily * 100)) ?>%"></div>
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

    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-5 text-[15px]">By source</h2>
        <div class="space-y-3">
            <?php foreach ($sourceBreakdown as $row): ?><div class="flex items-center justify-between"><span class="capitalize text-sm text-ink/60"><?= e(str_replace('_', ' ', $row['source'])) ?></span><span class="font-display font-semibold"><?= $row['total'] ?></span></div><?php endforeach; ?>
            <?php if (empty($sourceBreakdown)): ?><p class="text-sm text-ink/35 text-center py-10">No visit data yet.</p><?php endif; ?>
        </div>
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
