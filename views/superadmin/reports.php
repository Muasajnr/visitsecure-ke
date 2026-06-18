<?php
/** views/superadmin/reports.php */
$maxOrgVisits = max(array_column($visitsByOrg, 'total') ?: [1]);
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Platform Reports</h1>
    <p class="text-sm text-ink/55 mt-1">Platform-wide trends across all organisations.</p>
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

<div class="bg-white rounded-xl border border-ink/10 p-5">
    <h2 class="font-display font-semibold mb-4">New organisations per month (last 6 months)</h2>
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
