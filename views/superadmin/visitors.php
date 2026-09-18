<?php
/** views/superadmin/visitors.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Visitor management</h1>
    <p class="text-sm text-ink/55 mt-1">Search and manage visitor visit records across every organisation.</p>
</div>

<form method="GET" action="<?= url('/superadmin/visitors') ?>" class="bg-white rounded-xl border border-ink/10 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="min-w-[15rem] flex-1"><label class="block text-xs font-medium mb-1.5 text-ink/60">Search visitor</label><input type="search" name="search" value="<?= e($filters['search']) ?>" placeholder="Name, phone, email or company" class="w-full px-3 py-2 rounded-lg border border-ink/15 text-sm"></div>
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">Organisation</label><select name="organisation_id" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"><option value="0">All organisations</option><?php foreach ($organisations as $org): ?><option value="<?= $org['id'] ?>" <?= $filters['organisation_id'] == $org['id'] ? 'selected' : '' ?>><?= e($org['name']) ?></option><?php endforeach; ?></select></div>
    <div><label class="block text-xs font-medium mb-1.5 text-ink/60">Status</label><select name="status" class="px-3 py-2 rounded-lg border border-ink/15 text-sm"><option value="">All statuses</option><?php foreach (['pending','approved','rejected','checked_in','checked_out','expired','cancelled'] as $status): ?><option value="<?= $status ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>><?= e(ucwords(str_replace('_', ' ', $status))) ?></option><?php endforeach; ?></select></div>
    <button class="bg-ink text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brick transition">Filter</button>
</form>

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">
    <div class="px-5 py-4 border-b border-ink/10"><h2 class="font-display font-semibold"><?= count($visitors) ?> matching visit<?= count($visitors) === 1 ? '' : 's' ?></h2></div>
    <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left text-ink/50 text-xs uppercase tracking-wide border-b border-ink/10"><th class="px-5 py-3">Visitor</th><th class="px-5 py-3">Organisation</th><th class="px-5 py-3">Host / Location</th><th class="px-5 py-3">Created</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody>
<?php if (empty($visitors)): ?><tr><td colspan="6" class="px-5 py-10 text-center text-ink/40">No visitor records found.</td></tr><?php endif; ?>
<?php foreach ($visitors as $visitor): ?><tr class="border-b border-ink/5 last:border-0"><td class="px-5 py-3"><p class="font-medium"><?= e($visitor['visitor_name']) ?></p><p class="text-xs text-ink/45"><?= e($visitor['visitor_phone']) ?><?= $visitor['visitor_email'] ? ' · ' . e($visitor['visitor_email']) : '' ?></p></td><td class="px-5 py-3 text-ink/60"><?= e($visitor['organisation_name']) ?></td><td class="px-5 py-3 text-ink/60"><?= e($visitor['host_name'] ?? '-') ?><br><span class="text-xs text-ink/40"><?= e($visitor['building_name'] ?? '-') ?><?= $visitor['room_name'] ? ' · ' . e($visitor['room_name']) : '' ?></span></td><td class="px-5 py-3 text-ink/50"><?= formatDate($visitor['created_at']) ?></td><td class="px-5 py-3"><?= statusBadge($visitor['status']) ?></td><td class="px-5 py-3 text-right"><?php if (in_array($visitor['status'], ['pending','approved'], true)): ?><form method="POST" action="<?= url('/superadmin/visitors/' . $visitor['id'] . '/cancel') ?>" onsubmit="return confirmAction('Cancel this visitor visit?')"><?= csrfField() ?><button class="text-brick font-semibold text-xs hover:underline">Cancel visit</button></form><?php endif; ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
