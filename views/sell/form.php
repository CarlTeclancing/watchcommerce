<?php $title = 'Sell Your Watch'; include __DIR__ . '/../layouts/topbar.php'; ?>

<!-- Hero Banner with Image Slideshow -->
<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20 text-white h-80">
    <!-- Image Slideshow -->
    <div class="hero-slideshow absolute inset-0">
        <div class="hero-slide fade" style="background-image: url('https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=1400&q=80'); background-size: cover; background-position: center;"></div>
        <div class="hero-slide fade" style="background-image: url('https://images.unsplash.com/photo-1617634924702-92f37138b7c2?w=1400&q=80'); background-size: cover; background-position: center;"></div>
        <div class="hero-slide fade" style="background-image: url('https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=1400&q=80'); background-size: cover; background-position: center;"></div>
        <div class="hero-slide fade" style="background-image: url('https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=1400&q=80'); background-size: cover; background-position: center;"></div>
        <div class="hero-slide fade" style="background-image: url('https://images.unsplash.com/photo-1578127282290-831bfc70f9f5?w=1400&q=80'); background-size: cover; background-position: center;"></div>
    </div>
    
    <!-- Dark Overlay for Text Readability -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900/85 to-slate-900/75"></div>
    
    <!-- Content -->
    <div class="relative mx-auto max-w-3xl px-6 text-center h-full flex flex-col justify-center">
        <span class="mb-4 inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-1.5 text-sm font-semibold text-amber-300">
            <i class="fas fa-shield-alt text-xs"></i> Trusted Valuation Service
        </span>
        <h1 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Sell Your Luxury Watch</h1>
        <p class="mt-4 text-lg text-slate-300 max-w-xl mx-auto">
            Get a competitive, no-obligation quote from our experts. Fast, secure, and transparent.
        </p>
    </div>
</div>

<!-- Trust Badges -->
<div class="border-b border-slate-200 bg-white py-5 shadow-sm">
    <div class="mx-auto max-w-5xl px-6">
        <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-slate-600">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock text-amber-500"></i>
                <span>Quote within <strong>24 hours</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-lock text-amber-500"></i>
                <span><strong>Secure</strong> &amp; Confidential</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-amber-500"></i>
                <span><strong>Top market</strong> prices paid</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-amber-500"></i>
                <span><strong>No obligation</strong> — free valuation</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="mx-auto max-w-6xl px-6 py-16">
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">

        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="surface-card p-8 md:p-10">
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                        <i class="fas fa-tag text-amber-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Watch Details</h2>
                        <p class="text-sm text-slate-500">Fields marked <span class="text-red-500">*</span> are required</p>
                    </div>
                </div>

                <form method="POST" action="<?= $basePath ?? '' ?>/sell" class="space-y-6">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

                    <!-- Contact Info -->
                    <div>
                        <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Your Contact Info</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Full Name <span class="text-red-500">*</span></label>
                                <input class="form-control" name="contact_name" placeholder="John Smith" required>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                                <input class="form-control" name="contact_email" type="email" placeholder="you@example.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Watch Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Brand <span class="text-red-500">*</span></label>
                                <input class="form-control" name="brand" placeholder="e.g. Rolex, Patek Philippe" required>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Model <span class="text-red-500">*</span></label>
                                <input class="form-control" name="model_name" placeholder="e.g. Submariner, Nautilus" required>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Reference Number <span class="text-red-500">*</span></label>
                                <input class="form-control" name="reference_number" placeholder="e.g. 126610LN" required>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Condition <span class="text-red-500">*</span></label>
                                <select class="form-control" name="condition_grade" required>
                                    <option value="" disabled selected>Select condition…</option>
                                    <option value="excellent">Excellent — Like new, barely worn</option>
                                    <option value="very_good">Very Good — Minor wear, full working order</option>
                                    <option value="good">Good — Visible wear, fully functional</option>
                                    <option value="fair">Fair — Heavy wear or needs service</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Pricing</h3>
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Expected Price (USD) <span class="text-slate-400 font-normal">— optional</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 font-semibold">$</span>
                                <input class="form-control pl-8" name="asking_price" type="number" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Leave blank and we'll suggest a fair market price based on current demand.</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <button type="submit" class="btn-primary w-full py-4 text-base font-bold inline-flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Submit Valuation Request
                        </button>
                        <p class="mt-3 text-center text-xs text-slate-400">
                            By submitting you agree to our <a href="#" class="underline hover:text-slate-600">Privacy Policy</a>. No spam, ever.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- How It Works -->
            <div class="surface-card p-6">
                <h3 class="mb-5 flex items-center gap-2 font-bold text-slate-900">
                    <i class="fas fa-info-circle text-amber-500"></i> How It Works
                </h3>
                <ol class="space-y-4">
                    <li class="flex gap-3">
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">1</span>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Submit Your Details</div>
                            <div class="text-xs text-slate-500 mt-0.5">Fill in your watch details and contact info above.</div>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">2</span>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Receive a Quote</div>
                            <div class="text-xs text-slate-500 mt-0.5">Our experts review and send you a competitive offer within 24 hours.</div>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">3</span>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Accept &amp; Get Paid</div>
                            <div class="text-xs text-slate-500 mt-0.5">Accept the offer and receive fast, secure payment.</div>
                        </div>
                    </li>
                </ol>
            </div>

            <!-- Accepted Brands -->
            <div class="surface-card p-6">
                <h3 class="mb-4 flex items-center gap-2 font-bold text-slate-900">
                    <i class="fas fa-star text-amber-500"></i> Accepted Brands
                </h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach (['Rolex', 'Patek Philippe', 'Audemars Piguet', 'Omega', 'Cartier', 'IWC', 'Breitling', 'Vacheron', 'Richard Mille', 'Hublot'] as $brand): ?>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700"><?= htmlspecialchars($brand) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Contact Box -->
            <div class="rounded-xl p-6 text-white" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <h3 class="mb-2 font-bold">Need Help?</h3>
                <p class="text-sm text-slate-400 mb-4">Talk directly to one of our watch specialists.</p>
                <a href="mailto:info@watches.com" class="flex items-center gap-2 text-sm font-semibold text-amber-400 hover:text-amber-300">
                    <i class="fas fa-envelope"></i> info@watches.com
                </a>
                <a href="tel:+1234567890" class="mt-2 flex items-center gap-2 text-sm font-semibold text-amber-400 hover:text-amber-300">
                    <i class="fas fa-phone"></i> +1 (234) 567-890
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    // Hero Slideshow rotation
    function initHeroSlideshow() {
        const slides = document.querySelectorAll('.hero-slide');
        if (slides.length === 0) return;
        
        let currentSlide = 0;
        slides[currentSlide].classList.add('active');
        
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000); // Change slide every 5 seconds
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        initHeroSlideshow();
    });

    // Also initialize if already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroSlideshow);
    } else {
        initHeroSlideshow();
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
