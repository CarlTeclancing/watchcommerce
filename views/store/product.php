<?php
$title = $watch['title'];
include __DIR__ . '/../layouts/topbar.php';

// Parse gallery JSON
$gallery = [];
if (!empty($watch['gallery_json'])) {
    $decoded = json_decode($watch['gallery_json'], true);
    if (is_array($decoded)) {
        $gallery = $decoded;
    }
}
// Ensure hero is always index 0 (deduplicated)
$heroUrl = $watch['hero_image_url'] ?: 'https://placehold.co/900x600/1e293b/d4af37?text=Watch';
if (!in_array($heroUrl, $gallery, true)) {
    array_unshift($gallery, $heroUrl);
} else {
    // Move hero to front
    $gallery = array_merge([$heroUrl], array_filter($gallery, fn($u) => $u !== $heroUrl));
}
$gallery = array_values($gallery);
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-600">
        <a href="<?= $basePath ?? '' ?>/" class="hover:text-amber-500"><i class="fas fa-home"></i> Home</a>
        <span>/</span>
        <a href="<?= $basePath ?? '' ?>/watches" class="hover:text-amber-500">Shop</a>
        <span>/</span>
        <span class="text-slate-900 font-medium"><?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mb-12">

        <!-- ============================================================
             IMAGE GALLERY (takes 2/3 width on large screens)
             ============================================================ -->
        <div class="lg:col-span-2 space-y-3">

            <!-- Main image -->
            <div class="surface-card overflow-hidden rounded-2xl bg-slate-100" style="aspect-ratio:4/3;">
                <img id="mainWatchImage"
                     class="w-full h-full object-cover transition-opacity duration-300"
                     src="<?= htmlspecialchars($gallery[0] ?? $heroUrl, ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <!-- Thumbnails -->
            <?php if (count($gallery) > 1): ?>
            <div class="grid gap-2" style="grid-template-columns: repeat(<?= min(count($gallery), 5) ?>, 1fr);">
                <?php foreach ($gallery as $i => $imgUrl): ?>
                <button type="button"
                        onclick="switchImage(this, <?= htmlspecialchars(json_encode($imgUrl), ENT_QUOTES) ?>)"
                        class="gallery-thumb overflow-hidden rounded-xl border-2 transition
                               <?= $i === 0 ? 'border-amber-400 ring-2 ring-amber-300' : 'border-transparent hover:border-amber-300' ?>"
                        style="aspect-ratio:1/1;">
                    <img class="w-full h-full object-cover"
                         src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>"
                         alt="View <?= $i + 1 ?>">
                </button>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Placeholder thumbnails when no gallery -->
            <div class="grid grid-cols-4 gap-2 opacity-30">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="rounded-xl bg-slate-200 border-2 border-transparent" style="aspect-ratio:1/1;"></div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ============================================================
             PRODUCT INFO PANEL (1/3 width)
             ============================================================ -->
        <div class="surface-card rounded-2xl p-6 h-fit">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-widest font-bold" style="color:var(--luxury-gold)">
                    <?= htmlspecialchars($watch['brand_slug'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?>
                </span>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                    <?= htmlspecialchars($watch['condition_grade'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-1"><?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-slate-500 text-sm mb-4">Ref <strong class="text-slate-700"><?= htmlspecialchars($watch['reference_number'], ENT_QUOTES, 'UTF-8') ?></strong></p>

            <div class="border-t border-b border-slate-100 py-4 mb-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Price</p>
                <p class="text-4xl font-bold" style="color:var(--luxury-gold)">
                    $<?= number_format((float)$watch['list_price'], 0) ?>
                </p>
            </div>

            <!-- Specs -->
            <div class="mb-6 space-y-2 text-sm">
                <?php if (!empty($watch['year_of_production'])): ?>
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fas fa-calendar-alt text-amber-500 w-4 text-center"></i>
                    Year: <strong><?= htmlspecialchars((string)$watch['year_of_production'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <?php endif; ?>
                <?php if (!empty($watch['case_material'])): ?>
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fas fa-gem text-amber-500 w-4 text-center"></i>
                    Case: <strong><?= htmlspecialchars($watch['case_material'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <?php endif; ?>
                <?php if (!empty($watch['dial_color'])): ?>
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fas fa-palette text-amber-500 w-4 text-center"></i>
                    Dial: <strong><?= htmlspecialchars($watch['dial_color'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <?php endif; ?>
                <?php if (!empty($watch['movement'])): ?>
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fas fa-cog text-amber-500 w-4 text-center"></i>
                    Movement: <strong><?= htmlspecialchars($watch['movement'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <?php endif; ?>
                <?php if ($watch['includes_box_papers']): ?>
                <div class="flex items-center gap-2 text-emerald-600 font-semibold">
                    <i class="fas fa-check-circle w-4 text-center"></i>
                    Includes Box &amp; Papers
                </div>
                <?php endif; ?>
            </div>

            <!-- Add to cart -->
            <form method="POST" action="<?= $basePath ?? '' ?>/cart/items" class="mb-3">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="watch_id" value="<?= (int)$watch['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-primary w-full py-3.5 text-sm font-semibold inline-flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-bag"></i> Add to Cart
                </button>
            </form>

            <button class="btn-secondary w-full py-3 text-sm font-semibold inline-flex items-center justify-center gap-2 rounded-lg">
                <i class="fas fa-heart"></i> Add to Wishlist
            </button>

            <!-- Trust badges -->
            <div class="mt-5 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                <div class="flex items-center gap-2"><i class="fas fa-certificate text-emerald-500 w-4 text-center"></i> 100% Authenticated</div>
                <div class="flex items-center gap-2"><i class="fas fa-shipping-fast text-blue-500 w-4 text-center"></i> Fully Insured Shipping</div>
                <div class="flex items-center gap-2"><i class="fas fa-shield-alt text-amber-500 w-4 text-center"></i> Buyer Protection Guarantee</div>
            </div>
        </div>
    </div>

    <?php if (!empty($watch['description_html'])): ?>
    <div class="surface-card p-8 rounded-2xl">
        <h2 class="text-2xl font-bold text-slate-900 mb-4">About This Watch</h2>
        <div class="prose max-w-none text-slate-700">
            <?= $watch['description_html'] ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function switchImage(btn, url) {
    // Update main image
    const main = document.getElementById('mainWatchImage');
    main.style.opacity = '0';
    setTimeout(() => { main.src = url; main.style.opacity = '1'; }, 150);

    // Update active thumbnail ring
    document.querySelectorAll('.gallery-thumb').forEach(t => {
        t.classList.remove('border-amber-400', 'ring-2', 'ring-amber-300');
        t.classList.add('border-transparent');
    });
    btn.classList.remove('border-transparent');
    btn.classList.add('border-amber-400', 'ring-2', 'ring-amber-300');
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
