<?php $title = 'Order Placed'; include __DIR__ . '/../layouts/topbar.php'; ?>
<div class="rounded-xl bg-white p-8 shadow">
    <h1 class="text-3xl font-bold">Order Confirmed</h1>
    <p class="mt-2 text-slate-600">Order Number: <?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></p>
    <p class="text-slate-600">Status: <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></p>
    <p class="text-slate-600">Email: <?= htmlspecialchars($order['shipping_email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p class="text-slate-600">Phone: <?= htmlspecialchars($order['shipping_phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mt-4 text-xl font-semibold">Total: $<?= number_format((float)$order['total_amount'], 2) ?></p>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
