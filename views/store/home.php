<?php
$title = 'Luxury Watch Commerce - Authenticated Timepieces';
$heroImages = [
    [
        'url' => 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=1600&q=80',
        'alt' => 'Close-up of a luxury wristwatch with metallic bracelet',
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1617634924702-92f37138b7c2?auto=format&fit=crop&w=1600&q=80',
        'alt' => 'Modern luxury watch displayed on a dark surface',
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=1600&q=80',
        'alt' => 'Gold-toned watch face with premium detailing',
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=1600&q=80',
        'alt' => 'Luxury watch collection styled for editorial photography',
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1578127282290-831bfc70f9f5?auto=format&fit=crop&w=1600&q=80',
        'alt' => 'Elegant watch with leather strap in dramatic lighting',
    ],
];
include __DIR__ . '/../layouts/topbar.php';
?>

<section class="luxury-dark text-white px-4 relative overflow-hidden flex items-center justify-center" style="height: 70vh; min-height: 28rem;">
    <div id="hero-bg-a" class="absolute inset-0 z-0" style="background-image: url('<?= htmlspecialchars($heroImages[0]['url'], ENT_QUOTES, 'UTF-8') ?>'); background-size: cover; background-position: center; opacity: 1; transition: opacity 1.2s ease;"></div>
    <div id="hero-bg-b" class="absolute inset-0 z-0" style="background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s ease;"></div>

    <div class="absolute inset-0 z-10" style="background: linear-gradient(90deg, rgba(2, 6, 23, 0.84) 0%, rgba(15, 23, 42, 0.68) 50%, rgba(2, 6, 23, 0.74) 100%);"></div>

    <div class="relative z-20 mx-auto max-w-7xl w-full flex flex-col items-center justify-center text-center">
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

<!-- All Watches - 2 Column Feed with Load More -->
<section class="w-full py-20 px-4 bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold mb-2 text-center section-title">Explore More Watches</h2>
        <p class="text-center text-slate-600 mb-12 max-w-2xl mx-auto">Browse the collection in a clean two-column feed. Load more pieces as you go without leaving the landing page.</p>
        <div id="watches-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8 mb-12">
            <!-- Watches will be loaded here -->
        </div>
        <div class="flex justify-center">
            <button id="load-more-btn" class="btn-primary px-8 py-3" style="min-width: 14rem;">
                <i class="fas fa-sync-alt"></i> Load More Watches
            </button>
        </div>
    </div>
</section>

<script>
    const heroBackgrounds = <?= json_encode(array_column($heroImages, 'url'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    let currentPage = 1;
    let isLoading = false;
    const perPage = 4;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadWatches(page = 1) {
        if (isLoading) return;
        isLoading = true;
        
        const btn = document.getElementById('load-more-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        btn.disabled = true;

        fetch('<?= $basePath ?? '' ?>/api/v1/watches?page=' + page + '&per_page=' + perPage)
            .then(res => res.json())
            .then(data => {
                if (data.watches && data.watches.length > 0) {
                    const grid = document.getElementById('watches-grid');
                    
                    data.watches.forEach(watch => {
                        const watchCard = document.createElement('a');
                        watchCard.href = '<?= $basePath ?? '' ?>/watches/' + watch.slug;
                        watchCard.className = 'group block luxury-card surface-card overflow-hidden flex flex-col hover:shadow-lg transition-all';
                        
                        const imageUrl = watch.hero_image_url || 'https://placehold.co/500x500';
                        const price = new Intl.NumberFormat('en-US').format(watch.list_price);
                        const brand = escapeHtml(watch.brand_slug || 'Unknown').replace(/-/g, ' ');
                        const title = escapeHtml(watch.title);
                        const reference = escapeHtml(watch.reference_number || 'N/A');
                        const condition = escapeHtml(watch.condition_grade || 'Available');
                        const year = watch.year_of_production ? '<p class="text-xs text-slate-500 mt-1"><i class="fas fa-calendar"></i> ' + escapeHtml(watch.year_of_production) + '</p>' : '';
                        
                        watchCard.innerHTML = `
                            <div class="relative bg-slate-200 h-64 md:h-72 overflow-hidden">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-300" src="${escapeHtml(imageUrl)}" alt="${title}">
                                <div class="absolute inset-x-0 bottom-0 h-24" style="background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.4) 100%);"></div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <p class="text-xs uppercase text-slate-500 luxury-gold font-semibold" style="letter-spacing: 0.2em;">${brand}</p>
                                <h3 class="font-semibold text-lg mt-2">${title}</h3>
                                <p class="text-sm text-slate-600 mt-1">Ref ${reference}</p>
                                ${year}
                                <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                    <span class="text-lg font-bold">$${price}</span>
                                    <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded">${condition}</span>
                                </div>
                            </div>
                        `;
                        
                        grid.appendChild(watchCard);
                    });
                    
                    currentPage++;
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    isLoading = false;
                    
                    if (data.watches.length < perPage) {
                        btn.style.display = 'none';
                    }
                } else {
                    btn.style.display = 'none';
                }
            })
            .catch(err => {
                console.error('Error loading watches:', err);
                btn.innerHTML = originalText;
                btn.disabled = false;
                isLoading = false;
            });
    }

    // Load initial batch on page load
    document.addEventListener('DOMContentLoaded', () => {
        loadWatches(1);
    });

    // Load more on button click
    document.getElementById('load-more-btn').addEventListener('click', () => {
        loadWatches(currentPage);
    });

    function initHeroBackgroundSlider() {
        const layerA = document.getElementById('hero-bg-a');
        const layerB = document.getElementById('hero-bg-b');

        if (!layerA || !layerB || !Array.isArray(heroBackgrounds) || heroBackgrounds.length < 2) {
            return;
        }

        let current = 0;
        let showingA = true;

        setInterval(() => {
            const next = (current + 1) % heroBackgrounds.length;

            if (showingA) {
                layerB.style.backgroundImage = `url('${heroBackgrounds[next]}')`;
                layerB.style.opacity = '1';
                layerA.style.opacity = '0';
            } else {
                layerA.style.backgroundImage = `url('${heroBackgrounds[next]}')`;
                layerA.style.opacity = '1';
                layerB.style.opacity = '0';
            }

            showingA = !showingA;
            current = next;
        }, 4500);
    }

    document.addEventListener('DOMContentLoaded', initHeroBackgroundSlider);
</script>

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
