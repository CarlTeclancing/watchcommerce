<?php $title = 'Your Cart'; include __DIR__ . '/../layouts/topbar.php'; ?>

<div class="min-h-screen py-12 px-4">
    <div class="mx-auto max-w-4xl">

        <!-- Page heading -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Your Cart</h1>
            <p class="text-slate-500 mt-1">Review your items before checkout.</p>
        </div>

        <?php if (empty($cart)): ?>
        <!-- Empty state -->
        <div class="surface-card p-16 text-center">
            <i class="fas fa-shopping-cart text-5xl text-slate-300 mb-5 block"></i>
            <h2 class="text-xl font-semibold text-slate-700 mb-2">Your cart is empty</h2>
            <p class="text-slate-400 mb-8">Discover our curated collection of authenticated luxury timepieces.</p>
            <a href="<?= $basePath ?? '' ?>/watches" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold">
                <i class="fas fa-search"></i> Browse Watches
            </a>
        </div>

        <?php else: ?>
        <?php $total = 0; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Cart items (2/3 width) -->
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cart as $item):
                    $line = $item['quantity'] * $item['unit_price'];
                    $total += $line;
                ?>
                <div class="surface-card p-5 flex items-center gap-5">
                    <!-- Watch icon / image placeholder -->
                    <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-amber-500"></i>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 truncate">
                            <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Qty: <span class="font-medium text-slate-700"><?= (int)$item['quantity'] ?></span>
                            &nbsp;·&nbsp;
                            Unit: <span class="font-medium text-slate-700">$<?= number_format((float)$item['unit_price'], 2) ?></span>
                        </p>

                        <div class="mt-3 flex items-center gap-2">
                            <form method="POST" action="<?= $basePath ?? '' ?>/cart/items/update" class="flex items-center gap-2">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="watch_id" value="<?= (int)$item['watch_id'] ?>">
                                <input type="number" name="quantity" min="1" value="<?= (int)$item['quantity'] ?>" class="form-control w-20 text-sm py-1.5 px-2">
                                <button type="submit" class="btn-secondary inline-flex items-center gap-1 px-3 py-1.5 text-xs">
                                    <i class="fas fa-sync-alt"></i> Update
                                </button>
                            </form>

                            <form method="POST" action="<?= $basePath ?? '' ?>/cart/items/remove" onsubmit="return confirm('Remove this item from cart?');">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="watch_id" value="<?= (int)$item['watch_id'] ?>">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Line total -->
                    <div class="flex-shrink-0 text-right">
                        <p class="text-lg font-bold text-amber-600">$<?= number_format((float)$line, 2) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Order summary (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="surface-card p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-slate-900 mb-5 pb-4 border-b border-slate-100">
                        Order Summary
                    </h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-medium text-slate-900">$<?= number_format((float)$total, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping</span>
                            <span class="font-medium text-emerald-600">Free</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Insurance</span>
                            <span class="font-medium text-emerald-600">Included</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-base font-bold text-slate-900">Total</span>
                        <span class="text-2xl font-bold text-amber-600">$<?= number_format((float)$total, 2) ?></span>
                    </div>

                    <a href="<?= $basePath ?? '' ?>/checkout"
                       class="btn-primary w-full mt-6 inline-flex items-center justify-center gap-2 py-3.5 text-sm font-semibold">
                        <i class="fas fa-lock"></i> Secure Checkout
                    </a>

                    <a href="<?= $basePath ?? '' ?>/watches"
                       class="btn-secondary w-full mt-3 inline-flex items-center justify-center gap-2 py-3 text-sm font-semibold rounded-lg">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>

                    <p class="text-center text-xs text-slate-400 mt-4">
                        <i class="fas fa-shield-alt mr-1"></i> SSL Encrypted &amp; Secure
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
