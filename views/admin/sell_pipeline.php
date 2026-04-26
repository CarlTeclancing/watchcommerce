<?php $title = 'Sell Pipeline'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Sell Pipeline</h1>
                <p class="text-slate-600">Manage watch valuation requests</p>
            </div>
            <a href="<?= $basePath ?? '' ?>/admin" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                <i class="fas fa-arrow-left"></i>Back
            </a>
        </div>

        <div class="surface-card p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Sell Requests (<?= count($requests) ?>)</h2>
            </div>
            <div class="overflow-x-auto -mx-8 px-8">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-slate-200 bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Request ID</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Watch Details</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Status</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req): ?>
                        <tr class="border-b border-slate-100 hover:bg-green-50 transition">
                            <td class="px-4 py-4 font-medium text-slate-900">#<?= htmlspecialchars($req['id'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 text-slate-600">
                                <div class="font-medium text-slate-900"><?= htmlspecialchars($req['brand'] ?? '', ENT_QUOTES) ?> - <?= htmlspecialchars($req['model_name'] ?? '', ENT_QUOTES) ?></div>
                                <div class="text-xs text-slate-500">Ref: <?= htmlspecialchars($req['reference_number'] ?? '', ENT_QUOTES) ?></div>
                            </td>
                            <td class="px-4 py-4"><span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold"><?= htmlspecialchars($req['status'] ?? 'pending', ENT_QUOTES) ?></span></td>
                            <td class="px-4 py-4">
                                <form method="POST" action="<?= $basePath ?? '' ?>/admin/sell-pipeline/quote" class="flex gap-2">
                                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>">
                                    <input type="hidden" name="sell_request_id" value="<?= htmlspecialchars($req['id'] ?? '', ENT_QUOTES) ?>">
                                    <input type="text" name="offered_price" placeholder="Offered Price" class="form-control text-xs py-1 px-2 w-32" required>
                                    <button type="submit" class="btn-primary px-3 py-1 text-xs inline-flex items-center gap-1"><i class="fas fa-check text-xs"></i>Quote</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($requests)): ?>
            <p class="text-center text-slate-500 py-12"><i class="fas fa-inbox text-3xl mb-3 block opacity-30"></i>No pending sell requests</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
