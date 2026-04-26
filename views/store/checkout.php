<?php $title = 'Checkout'; include __DIR__ . '/../layouts/topbar.php'; ?>
<h1 class="text-2xl font-bold">Checkout</h1>
<form class="mt-6 space-y-4 rounded-xl bg-white p-6 shadow" method="POST" action="<?= $basePath ?? '' ?>/checkout">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
    <input class="w-full rounded border border-slate-300 px-3 py-2" name="shipping_name" placeholder="Full name" required>
    <input class="w-full rounded border border-slate-300 px-3 py-2" name="shipping_email" placeholder="Email" type="email" required>
    <input class="w-full rounded border border-slate-300 px-3 py-2" name="shipping_phone" placeholder="Phone number" type="tel" required>
    <textarea class="w-full rounded border border-slate-300 px-3 py-2" name="shipping_address" placeholder="Shipping address" required></textarea>
    <button class="rounded bg-amber-500 px-5 py-3 font-semibold text-slate-900">Place Order</button>
</form>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
