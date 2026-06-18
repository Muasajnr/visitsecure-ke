<?php
/** views/shared/notifications.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Notifications</h1>
    <p class="text-sm text-ink/55 mt-1">Updates about your visits, approvals, and gate passes.</p>
</div>

<div class="space-y-3 max-w-2xl">
    <?php if (empty($notifications)): ?>
        <div class="bg-white rounded-xl border border-ink/10 p-10 text-center text-ink/40">No notifications yet.</div>
    <?php endif; ?>
    <?php foreach ($notifications as $n): ?>
        <div class="bg-white rounded-xl border <?= $n['is_read'] ? 'border-ink/10' : 'border-brick/30 bg-brick/5' ?> p-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-semibold text-sm"><?= e($n['title']) ?></p>
                    <p class="text-sm text-ink/60 mt-1"><?= e($n['message']) ?></p>
                    <p class="text-xs text-ink/40 mt-2"><?= formatDate($n['created_at']) ?></p>
                </div>
                <?php if (!$n['is_read']): ?>
                <form method="POST" action="<?= url('/notifications/' . $n['id'] . '/read') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="text-xs text-brick font-semibold whitespace-nowrap">Mark read</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
