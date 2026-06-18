<?php
/** views/orgadmin/reports.php */
$maxMonthly = max(array_column($monthlyTrend, 'total') ?: [1]);
$maxBuilding = max(array_column($byBuilding, 'total') ?: [1]);
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Analytics &amp; Reports</h1>
    <p class="text-sm text-ink/55 mt-1">Visit trends and breakdowns across your organisation.</p>
</div>

<div class="grid md:grid-cols-2 gap-6 mb-6">
    <!-- Monthly trend -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Visits per month (last 6 months)</h2>
        <?php if (empty($monthlyTrend)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($monthlyTrend as $row): ?>
                    <div>
                        <div class="flex justify-between text-xs text-ink/50 mb-1">
                            <span><?= e($row['ym']) ?></span><span><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2.5 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-brick rounded-full" style="width: <?= max(4, round($row['total'] / $maxMonthly * 100)) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Status breakdown -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Visit status breakdown</h2>
        <?php if (empty($statusBreakdown)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($statusBreakdown as $row): ?>
                    <div class="flex items-center justify-between">
                        <span><?= statusBadge($row['status']) ?></span>
                        <span class="font-display font-semibold"><?= $row['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <!-- By building -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Visits by building</h2>
        <?php if (empty($byBuilding)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No buildings yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($byBuilding as $row): ?>
                    <div>
                        <div class="flex justify-between text-xs text-ink/50 mb-1">
                            <span><?= e($row['name']) ?></span><span><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2.5 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-ink rounded-full" style="width: <?= max(4, round($row['total'] / $maxBuilding * 100)) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Top hosts -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Most-visited hosts</h2>
        <?php if (empty($topHosts)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
        <?php else: ?>
            <ol class="space-y-2">
                <?php foreach ($topHosts as $i => $h): ?>
                    <li class="flex items-center justify-between text-sm">
                        <span class="font-medium"><?= $i+1 ?>. <?= e($h['full_name']) ?></span>
                        <span class="text-ink/50"><?= $h['total'] ?> visits</span>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</div>
