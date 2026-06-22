<?php
/** views/shared/notifications.php */
?>
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="font-display text-2xl font-semibold">Notifications</h1>
        <p class="text-sm text-ink/50 mt-1">
            <?php if (!empty($notifications)): ?>
                <?= count(array_filter($notifications, fn($n) => !$n['is_read'])) ?> unread
            <?php else: ?>
                You're all caught up
            <?php endif; ?>
        </p>
    </div>
    <?php if (!empty($notifications)): ?>
    <form method="POST" action="<?= url('/notifications/mark-all-read') ?>">
        <?= csrfField() ?>
        <button type="submit" class="text-sm text-brick font-semibold hover:underline">Mark all as read</button>
    </form>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden max-w-2xl">
    <?php if (empty($notifications)): ?>
        <div class="py-16 text-center">
            <div class="w-12 h-12 rounded-full bg-ink/5 flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm text-ink/40">No notifications yet — you'll see visit approvals,<br>check-ins, and other updates here.</p>
        </div>
    <?php else: ?>
        <ul class="divide-y divide-ink/5">
            <?php foreach ($notifications as $n): ?>
            <li class="px-5 py-4 flex items-start gap-3 <?= $n['is_read'] ? 'opacity-60' : 'bg-blue-50/30' ?>">
                <span class="mt-0.5 w-2 h-2 rounded-full shrink-0 <?= $n['is_read'] ? 'bg-transparent' : 'bg-brick' ?>"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium leading-snug"><?= e($n['title'] ?? 'Notification') ?></p>
                    <?php if (!empty($n['message'])): ?>
                        <p class="text-sm text-ink/55 mt-1 leading-relaxed"><?= e($n['message']) ?></p>
                    <?php endif; ?>
                    <p class="text-[11px] text-ink/35 mt-1.5"><?= formatDate($n['created_at']) ?></p>
                </div>
                <?php if (!$n['is_read']): ?>
                <form method="POST" action="<?= url('/notifications/' . $n['id'] . '/read') ?>">
                    <?= csrfField() ?>
                    <button type="submit" class="text-[11px] text-ink/40 hover:text-ink shrink-0 mt-0.5">Mark read</button>
                </form>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
