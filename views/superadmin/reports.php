<?php
/** views/superadmin/reports.php */
$maxOrgVisits = max(array_column($visitsByOrg, 'total') ?: [1]);
?>
<div class="mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div><h1 class="font-display text-2xl font-semibold">Platform reports</h1><p class="text-sm text-ink/55 mt-1">Cross-organisation visitor activity and subscription intelligence.</p></div>
        <a href="<?= url('/superadmin/reports/export?from=' . urlencode($filters['from']) . '&to=' . urlencode($filters['to'])) ?>" class="bg-ink text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-brick transition">Download CSV</a>
    </div>
</div>

<form method="GET" action="<?= url('/superadmin/reports') ?>" class="bg-white rounded-xl border border-ink/10 p-4 mb-6 flex flex-wrap items-end gap-3">
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">From</label><input type="date" name="from" value="<?= e($filters['from']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"></div>
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">To</label><input type="date" name="to" value="<?= e($filters['to']) ?>" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"></div>
    <button class="bg-brick text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-ink transition">Apply period</button>
</form>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php foreach ([['Visits', $summary['total'] ?? 0], ['Organisations active', $summary['organisations'] ?? 0], ['Walk-ins', $summary['walk_ins'] ?? 0], ['Avg. stay', ($summary['avg_duration'] ?? 0) . ' min']] as $metric): ?>
        <div class="bg-white rounded-xl border border-ink/10 p-5"><p class="text-xs text-ink/50 uppercase tracking-wide font-semibold"><?= e($metric[0]) ?></p><p class="font-display text-2xl font-semibold mt-1"><?= e((string)$metric[1]) ?></p></div>
    <?php endforeach; ?>
</div>

<div class="grid md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Most active organisations (by visits)</h2>
        <?php if (empty($visitsByOrg)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($visitsByOrg as $row): ?>
                    <div>
                        <div class="flex justify-between text-xs text-ink/50 mb-1">
                            <span><?= e($row['name']) ?></span><span><?= $row['total'] ?></span>
                        </div>
                        <div class="h-2.5 bg-ink/5 rounded-full overflow-hidden">
                            <div class="h-full bg-brick rounded-full" style="width: <?= max(4, round($row['total'] / $maxOrgVisits * 100)) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Subscription status breakdown</h2>
        <?php if (empty($planBreakdown)): ?>
            <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($planBreakdown as $row): ?>
                    <div class="flex items-center justify-between">
                        <span><?= statusBadge($row['subscription_status']) ?></span>
                        <span class="font-display font-semibold"><?= $row['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Visit status</h2>
        <div class="space-y-3"><?php foreach ($statusBreakdown as $row): ?><div class="flex items-center justify-between"><?= statusBadge($row['status']) ?><span class="font-semibold"><?= $row['total'] ?></span></div><?php endforeach; ?></div>
    </div>
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-4">Visit source</h2>
        <div class="space-y-3"><?php foreach ($sourceBreakdown as $row): ?><div class="flex items-center justify-between"><span class="capitalize text-sm text-ink/60"><?= e(str_replace('_', ' ', $row['source'])) ?></span><span class="font-semibold"><?= $row['total'] ?></span></div><?php endforeach; ?></div>
    </div>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-5 mb-6">
    <h2 class="font-display font-semibold mb-4">Daily platform activity</h2>
    <?php $maxDaily = max(array_column($dailyTrend, 'total') ?: [1]); ?>
    <?php if (empty($dailyTrend)): ?><p class="text-sm text-ink/40 py-6 text-center">No visit data for this period.</p><?php else: ?>
        <div class="space-y-2"><?php foreach ($dailyTrend as $row): ?><div class="flex items-center gap-3 text-xs"><span class="w-24 text-ink/55 shrink-0"><?= e($row['day']) ?></span><div class="flex-1 h-2 bg-ink/5 rounded-full overflow-hidden"><div class="h-full bg-brick rounded-full" style="width: <?= max(2, round($row['total'] / $maxDaily * 100)) ?>%"></div></div><span class="w-8 text-right font-semibold"><?= $row['total'] ?></span></div><?php endforeach; ?></div>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl border border-ink/10 p-5">
    <h2 class="font-display font-semibold mb-4">New organisations in selected period</h2>
    <?php if (empty($signupsTrend)): ?>
        <p class="text-sm text-ink/40 py-8 text-center">No data yet.</p>
    <?php else: ?>
        <div class="flex items-end gap-4 h-32">
            <?php $maxSignup = max(array_column($signupsTrend, 'total') ?: [1]); ?>
            <?php foreach ($signupsTrend as $row): ?>
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-ink rounded-t-md" style="height: <?= max(8, round($row['total'] / $maxSignup * 100)) ?>%"></div>
                    <span class="text-xs text-ink/50"><?= e(substr($row['ym'], 5)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
