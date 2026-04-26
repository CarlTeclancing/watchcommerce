<?php
declare(strict_types=1);

$cartItems = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Luxury Watch Commerce', ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .luxury-gold { color: #d4af37; }
        .luxury-dark { background-color: #0f172a; }
        .luxury-card { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
        .luxury-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .trust-badge { background-color: #1e293b; color: #e2e8f0; padding: 0.5rem 1rem; font-size: 0.875rem; text-align: center; border-bottom: 1px solid #334155; }
        .header-top { background-color: #0f172a; color: #e2e8f0; padding: 1rem; display: flex; align-items: center; justify-content: space-between; }
        .header-main { background-color: white; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .header-logo { font-size: 2rem; font-weight: bold; letter-spacing: 3px; color: #0f172a; text-decoration: none; }
        .nav-link { color: #0f172a; text-decoration: none; font-size: 0.95rem; transition: color 0.2s; }
        .nav-link:hover { color: #d4af37; }
        .icon-link { color: #0f172a; text-decoration: none; font-size: 1.25rem; margin: 0 1rem; transition: color 0.2s; }
        .icon-link:hover { color: #d4af37; }
        .header-top .icon-link { color: #e2e8f0; margin: 0; }
        .header-top .icon-link:hover { color: #d4af37; }
        .icon-btn { position: relative; color: #0f172a; text-decoration: none; font-size: 1.1rem; transition: color 0.2s; }
        .icon-btn:hover { color: #d4af37; }
        .header-top .icon-btn { color: #e2e8f0; }
        .cart-badge {
            position: absolute;
            top: -0.45rem;
            right: -0.6rem;
            min-width: 1.15rem;
            height: 1.15rem;
            border-radius: 9999px;
            background: #d4af37;
            color: #0f172a;
            font-size: 0.7rem;
            line-height: 1.15rem;
            font-weight: 700;
            text-align: center;
            padding: 0 0.2rem;
        }
        .header-container { max-width: 1280px; margin: 0 auto; }
        @media (max-width: 768px) {
            .header-logo { font-size: 1.9rem; letter-spacing: 1px; }
            .header-main { padding: 0.9rem 1rem; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="trust-badge">
    <i class="fas fa-shield-halved"></i> 100% Certified Authentic Luxury Watches
</div>

<div class="header-top px-4 md:px-8">
    <div class="header-container w-full flex items-center justify-between">
        <div class="hidden sm:flex gap-4">
            <a href="tel:+1234567890" class="icon-link"><i class="fas fa-phone"></i></a>
        </div>
        <div class="flex items-center gap-4 text-slate-100">
            <a href="<?= $basePath ?? '' ?>/cart" class="icon-btn" aria-label="Open cart">
                <i class="fas fa-shopping-bag"></i>
                <?php if ($cartCount > 0): ?>
                <span class="cart-badge"><?= $cartCount > 99 ? '99+' : $cartCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= $basePath ?? '' ?>/login" class="icon-link"><i class="fas fa-user"></i></a>
            <button class="icon-link md:hidden" id="menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<div class="header-main px-4 md:px-8">
    <div class="header-container w-full">
        <div class="flex flex-wrap md:flex-nowrap items-center gap-4 md:gap-8">
            <a href="<?= $basePath ?? '' ?>/" class="header-logo text-4xl md:text-5xl">WATCHES</a>
            <div class="w-full md:flex-1 order-3 md:order-none">
                <input type="text" placeholder="Search watches..." class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="hidden md:flex items-center gap-6 ml-auto">
                <a href="<?= $basePath ?? '' ?>/watches" class="nav-link">Shop</a>
                <a href="<?= $basePath ?? '' ?>/sell" class="nav-link">Sell</a>
                <a href="<?= $basePath ?? '' ?>/faq" class="nav-link">FAQ</a>
                <a href="<?= $basePath ?? '' ?>/admin" class="nav-link">Admin</a>
                <a href="<?= $basePath ?? '' ?>/cart" class="icon-btn" aria-label="Open cart">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount > 99 ? '99+' : $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</div>

<nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-200 px-4 py-4 shadow-sm" aria-hidden="true">
    <div class="flex flex-col gap-3">
        <a href="<?= $basePath ?? '' ?>/watches" class="nav-link">Shop</a>
        <a href="<?= $basePath ?? '' ?>/sell" class="nav-link">Sell</a>
        <a href="<?= $basePath ?? '' ?>/faq" class="nav-link">FAQ</a>
        <a href="<?= $basePath ?? '' ?>/admin" class="nav-link">Admin</a>
        <a href="<?= $basePath ?? '' ?>/cart" class="nav-link flex items-center gap-2">
            <i class="fas fa-shopping-bag"></i>
            <span>Cart<?= $cartCount > 0 ? ' (' . ($cartCount > 99 ? '99+' : $cartCount) . ')' : '' ?></span>
        </a>
    </div>
</nav>

<main class="bg-slate-50">
