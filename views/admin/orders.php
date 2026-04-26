<?php $title = 'Orders'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Orders</h1>
                <p class="text-slate-600">Track and manage customer orders</p>
            </div>
            <a href="<?= $basePath ?? '' ?>/admin" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                <i class="fas fa-arrow-left"></i>Back
            </a>
        </div>

        <div class="surface-card p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-receipt text-blue-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Recent Orders (<?= count($orders) ?>)</h2>
            </div>
            <div class="overflow-x-auto -mx-8 px-8">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-slate-200 bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Order ID</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Order Number</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Customer</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Total</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Status</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Date</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr class="border-b border-slate-100 hover:bg-blue-50 transition">
                            <td class="px-4 py-4 font-medium text-slate-900">#<?= htmlspecialchars($order['id'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 text-slate-600"><?= htmlspecialchars($order['order_number'] ?? 'N/A', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div><?= htmlspecialchars($order['shipping_email'] ?? '', ENT_QUOTES) ?></div>
                                <div><?= htmlspecialchars($order['shipping_phone'] ?? '', ENT_QUOTES) ?></div>
                            </td>
                            <td class="px-4 py-4 font-semibold text-blue-600">$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                            <td class="px-4 py-4"><span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"><?= htmlspecialchars($order['status'] ?? '', ENT_QUOTES) ?></span></td>
                            <td class="px-4 py-4 text-slate-600 text-xs"><?= htmlspecialchars($order['created_at'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4">
                                <form method="POST" action="<?= $basePath ?? '' ?>/admin/orders/status" class="flex items-center gap-2">
                                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="order_id" value="<?= (int)($order['id'] ?? 0) ?>">
                                    <select name="status" class="form-control text-xs py-1.5 px-2 min-w-36">
                                        <option value="pending_payment" <?= ($order['status'] ?? '') === 'pending_payment' ? 'selected' : '' ?>>Pending Payment</option>
                                        <option value="paid" <?= ($order['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="processing" <?= ($order['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="shipped" <?= ($order['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                        <option value="delivered" <?= ($order['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                        <option value="cancelled" <?= ($order['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        <option value="refunded" <?= ($order['status'] ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                                    </select>
                                    <button type="submit" class="btn-primary px-3 py-1.5 text-xs inline-flex items-center gap-1">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($orders)): ?>
            <p class="text-center text-slate-500 py-12"><i class="fas fa-inbox text-3xl mb-3 block opacity-30"></i>No orders yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
