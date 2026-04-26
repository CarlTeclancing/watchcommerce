</main>

<?php
$appSettings = $app_settings ?? [];
$siteName = (string)($appSettings['site_name'] ?? 'WATCHES');
$supportEmail = (string)($appSettings['support_email'] ?? 'info@watches.com');
$supportPhone = (string)($appSettings['support_phone'] ?? '+1 (234) 567-890');
$footerTagline = (string)($appSettings['footer_tagline'] ?? 'Authenticated luxury watches from around the world.');
$footerCopyright = (string)($appSettings['footer_copyright'] ?? 'Luxury Watch Commerce. All rights reserved.');
?>

<footer class="bg-slate-900 text-slate-100 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h3 class="text-xl font-bold mb-4 luxury-gold"><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="text-slate-400"><?= htmlspecialchars($footerTagline, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Shop</h4>
            <ul class="text-slate-400 space-y-2 text-sm">
                <li><a href="<?= $basePath ?? '' ?>/watches" class="hover:text-amber-400 transition">Browse All</a></li>
                <li><a href="<?= $basePath ?? '' ?>/watches" class="hover:text-amber-400 transition">New Arrivals</a></li>
                <li><a href="<?= $basePath ?? '' ?>/watches" class="hover:text-amber-400 transition">Sale</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Sell</h4>
            <ul class="text-slate-400 space-y-2 text-sm">
                <li><a href="<?= $basePath ?? '' ?>/sell" class="hover:text-amber-400 transition">Sell Your Watch</a></li>
                <li><a href="<?= $basePath ?? '' ?>/" class="hover:text-amber-400 transition">Valuations</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Contact</h4>
            <ul class="text-slate-400 space-y-2 text-sm">
                <li><a href="mailto:<?= htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8') ?>" class="hover:text-amber-400 transition"><?= htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8') ?></a></li>
                <li><a href="tel:<?= htmlspecialchars($supportPhone, ENT_QUOTES, 'UTF-8') ?>" class="hover:text-amber-400 transition"><?= htmlspecialchars($supportPhone, ENT_QUOTES, 'UTF-8') ?></a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-700 mt-8 pt-8 text-center text-slate-400 text-sm">
        <p>&copy; 2026 <?= htmlspecialchars($footerCopyright, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</footer>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            if (!mobileMenu) return;
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
            mobileMenu.setAttribute('aria-hidden', isExpanded ? 'true' : 'false');
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>
</html>
