<?php $title = 'Browse Luxury Watches'; include __DIR__ . '/../layouts/topbar.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2 section-title">Our Collection</h1>
        <p class="text-slate-600">Explore our curated selection of authenticated luxury timepieces</p>
    </div>

    <div class="mb-8 surface-card p-6">
        <form method="GET" action="<?= $basePath ?? '' ?>/watches" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="block text-sm font-medium mb-2">Search</label>
                <input type="text" name="q" placeholder="Model or reference..." class="form-control" value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Brand</label>
                <input type="text" name="brand" placeholder="e.g., rolex" class="form-control" value="<?= htmlspecialchars($_GET['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Sort By</label>
                <select name="sort" class="form-control">
                    <option value="">Latest</option>
                    <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary py-2 px-4"><i class="fas fa-search"></i> Search</button>
            </div>
            <div class="flex items-end">
                <a href="<?= $basePath ?? '' ?>/watches" class="w-full text-center btn-secondary py-2 px-4"><i class="fas fa-redo"></i> Clear</a>
            </div>
        </form>
    </div>

    <div class="mb-4 text-slate-600">
        <p>Showing <strong><?= count($watches) ?></strong> timepiece<?= count($watches) !== 1 ? 's' : '' ?></p>
    </div>

    <?php if (!empty($watches)): ?>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <?php foreach ($watches as $watch): ?>
        <a href="<?= $basePath ?? '' ?>/watches/<?= htmlspecialchars($watch['slug'], ENT_QUOTES, 'UTF-8') ?>" class="block luxury-card surface-card overflow-hidden group">
            <div class="relative bg-slate-200 h-56 overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-300" src="<?= htmlspecialchars($watch['hero_image_url'] ?: 'https://placehold.co/400x400', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="absolute top-3 right-3 chip bg-amber-400 text-slate-900">
                    <?= htmlspecialchars($watch['condition_grade'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
            <div class="p-4">
                <p class="text-xs uppercase tracking-wider luxury-gold font-semibold"><?= htmlspecialchars($watch['brand_slug'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
                <h3 class="font-semibold text-lg mt-2 group-hover:text-amber-600 transition"><?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="text-sm text-slate-600 mt-1">Ref <?= htmlspecialchars($watch['reference_number'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php if (!empty($watch['year_of_production'])): ?>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-calendar"></i> <?= htmlspecialchars($watch['year_of_production'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <div class="mt-4 pt-4 border-t">
                    <span class="text-lg font-bold">$<?= number_format((float)$watch['list_price'], 0) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-slate-50 p-12 rounded-lg text-center">
        <i class="fas fa-search text-4xl text-slate-300 mb-4 block"></i>
        <h3 class="text-2xl font-semibold mb-2">No watches found</h3>
        <p class="text-slate-600 mb-6">Try adjusting your search criteria</p>
        <a href="<?= $basePath ?? '' ?>/watches" class="inline-block bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold py-2 px-6 rounded transition">View All Watches</a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
