<?php $title = 'Luxury Watch Commerce - Authenticated Timepieces'; include __DIR__ . '/../layouts/topbar.php'; ?>

<section class="luxury-dark text-white py-16 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 section-title">Exquisite Timepieces</h1>
        <p class="text-lg md:text-xl text-slate-300 mb-8 max-w-3xl mx-auto">Discover curated collections of authenticated luxury watches from the world's most prestigious brands.</p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="<?= $basePath ?? '' ?>/watches" class="btn-primary px-8 py-3"><i class="fas fa-search"></i> Browse Inventory</a>
            <a href="<?= $basePath ?? '' ?>/sell" class="btn-secondary px-8 py-3"><i class="fas fa-tag"></i> Get Valuation</a>
        </div>
    </div>
</section>

<?php if (!empty($brands)): ?>
<section class="py-12 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 text-center section-title"><span class="luxury-gold">Premium</span> Brands</h2>
        <div class="swiper category-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($brands as $brand): ?>
                <div class="swiper-slide">
                    <a href="<?= $basePath ?? '' ?>/watches?brand=<?= htmlspecialchars($brand['brand_slug'], ENT_QUOTES, 'UTF-8') ?>" class="block text-center p-6 luxury-card surface-card">
                        <div class="text-5xl mb-3"><i class="fas fa-clock"></i></div>
                        <h3 class="font-semibold text-lg uppercase"><?= htmlspecialchars($brand['brand_name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="text-slate-600 text-sm mt-2"><?= $brand['count'] ?> Watches</p>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($featured)): ?>
<section class="py-12 px-4 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-2 text-center section-title">Featured Collection</h2>
        <p class="text-center text-slate-600 mb-8">Hand-selected timepieces curated for discerning collectors</p>
        <div class="swiper featured-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($featured as $watch): ?>
                <div class="swiper-slide">
                    <a href="<?= $basePath ?? '' ?>/watches/<?= htmlspecialchars($watch['slug'], ENT_QUOTES, 'UTF-8') ?>" class="block luxury-card surface-card overflow-hidden h-full flex flex-col">
                        <div class="relative bg-slate-200 h-64 overflow-hidden">
                            <img class="w-full h-full object-cover" src="<?= htmlspecialchars($watch['hero_image_url'] ?: 'https://placehold.co/400x400', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="absolute top-3 right-3 chip bg-amber-400 text-slate-900">Featured <?= htmlspecialchars($watch['condition_grade'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs uppercase tracking-wider text-slate-500 luxury-gold font-semibold"><?= htmlspecialchars($watch['brand_slug'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
                            <h3 class="font-semibold text-lg mt-2"><?= htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="text-sm text-slate-600 mt-1">Ref <?= htmlspecialchars($watch['reference_number'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($watch['year_of_production'])): ?>
                            <p class="text-xs text-slate-500 mt-1"><i class="fas fa-calendar"></i> <?= htmlspecialchars($watch['year_of_production'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                <span class="text-lg font-bold">$<?= number_format((float)$watch['list_price'], 0) ?></span>
                                <i class="fas fa-heart text-slate-300 hover:text-red-500 cursor-pointer transition"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-12 text-center section-title">Why Choose Us</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl mb-4 luxury-gold"><i class="fas fa-certificate"></i></div>
                <h3 class="font-semibold text-lg mb-2">100% Authentic</h3>
                <p class="text-slate-600">Every timepiece is verified and authenticated by our expert team.</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-4 luxury-gold"><i class="fas fa-shield-halved"></i></div>
                <h3 class="font-semibold text-lg mb-2">Buyer Protection</h3>
                <p class="text-slate-600">Full refund guarantee if not satisfied with your purchase.</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-4 luxury-gold"><i class="fas fa-shipping-fast"></i></div>
                <h3 class="font-semibold text-lg mb-2">Secure Shipping</h3>
                <p class="text-slate-600">Insured and tracked delivery worldwide.</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-4 luxury-gold"><i class="fas fa-redo"></i></div>
                <h3 class="font-semibold text-lg mb-2">Easy Returns</h3>
                <p class="text-slate-600">30-day return policy, no questions asked.</p>
            </div>
        </div>
    </div>
</section>

<script>
    if (typeof Swiper !== 'undefined') {
        new Swiper('.category-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 5000 },
            navigation: {
                nextEl: '.category-swiper .swiper-button-next',
                prevEl: '.category-swiper .swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 },
            }
        });

        new Swiper('.featured-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 6000 },
            navigation: {
                nextEl: '.featured-swiper .swiper-button-next',
                prevEl: '.featured-swiper .swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            }
        });
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
