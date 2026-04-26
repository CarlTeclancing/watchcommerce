<?php
declare(strict_types=1);

$cartItems = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}
$appSettings = $app_settings ?? [];
$siteName = (string)($appSettings['site_name'] ?? 'WATCHES');
$supportPhone = (string)($appSettings['support_phone'] ?? '+1 (234) 567-890');
$supportEmail = (string)($appSettings['support_email'] ?? 'info@watches.com');
$heroBadgeText = (string)($appSettings['hero_badge_text'] ?? '100% Certified Authentic Luxury Watches');
$logoUrl = (string)($appSettings['logo_url'] ?? '');
$faviconUrl = (string)($appSettings['favicon_url'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? $siteName, ENT_QUOTES, 'UTF-8') ?></title>
    <?php if ($faviconUrl !== ''): ?>
    <link rel="icon" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>/assets/vendor/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>/assets/vendor/swiper-bundle.min.css">
    <script src="<?= $basePath ?? '' ?>/assets/vendor/swiper-bundle.min.js"></script>
    <style>
        :root {
            --luxury-gold: #d4af37;
            --luxury-dark: #0f172a;
            --ink: #0b1220;
            --muted: #64748b;
            --surface: #ffffff;
            --ring: rgba(212, 175, 55, 0.35);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 520px at 100% -10%, rgba(212, 175, 55, 0.11), transparent 55%),
                linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
        }

        a, button, input, select {
            transition: all 0.2s ease;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px var(--ring);
            border-radius: 0.5rem;
        }

        .luxury-gold { color: #d4af37; }
        .luxury-dark { background-color: #0f172a; }
        .luxury-card { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s; }
        .luxury-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); }

        .surface-card {
            background: var(--surface);
            border: 1px solid #e2e8f0;
            border-radius: 0.9rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .form-control {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 0.65rem;
            background: #fff;
            color: var(--ink);
            padding: 0.62rem 0.78rem;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f3c54a, #d4af37);
            color: #0f172a;
            border: 1px solid #c9a62f;
            border-radius: 0.65rem;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(212, 175, 55, 0.25);
        }

        .btn-primary:hover {
            filter: brightness(1.03);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #fff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            border-radius: 0.65rem;
            font-weight: 600;
        }

        .btn-secondary:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .section-title {
            letter-spacing: -0.02em;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<header class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/95 backdrop-blur">
    <div class="bg-slate-900 text-slate-100 text-sm py-2 text-center"><?= htmlspecialchars($heroBadgeText, ENT_QUOTES, 'UTF-8') ?></div>
    <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between gap-3">
        <a href="<?= $basePath ?? '' ?>/" class="text-2xl font-bold tracking-wide text-slate-900 flex items-center gap-2">
            <?php if ($logoUrl !== ''): ?>
            <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>" class="h-8 w-auto object-contain">
            <?php else: ?>
            <span><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </a>
        <nav class="hidden md:flex items-center gap-5">
            <a href="<?= $basePath ?? '' ?>/watches" class="text-slate-700 hover:text-amber-600 font-medium">Shop</a>
            <a href="<?= $basePath ?? '' ?>/sell" class="text-slate-700 hover:text-amber-600 font-medium">Sell</a>
            <a href="<?= $basePath ?? '' ?>/faq" class="text-slate-700 hover:text-amber-600 font-medium">FAQ</a>
            <a href="<?= $basePath ?? '' ?>/register" class="text-slate-700 hover:text-amber-600 font-medium">Register</a>
            <a href="<?= $basePath ?? '' ?>/cart" class="relative text-slate-800 hover:text-amber-600" aria-label="Open cart">
                <i class="fas fa-shopping-bag"></i>
                <?php if ($cartCount > 0): ?>
                <span class="absolute -top-2 -right-3 min-w-4 h-4 rounded-full bg-amber-500 text-slate-900 text-[10px] font-bold text-center leading-4 px-1"><?= $cartCount > 99 ? '99+' : $cartCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= $basePath ?? '' ?>/login" class="text-slate-800 hover:text-amber-600 font-medium">Login</a>
        </nav>
        <button id="menu-toggle" class="md:hidden text-slate-800 p-2" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <nav id="mobile-menu" class="hidden md:hidden px-4 pb-4 flex flex-col gap-2 border-t border-slate-200 bg-white">
        <a href="<?= $basePath ?? '' ?>/watches" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">Shop</a>
        <a href="<?= $basePath ?? '' ?>/sell" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">Sell</a>
        <a href="<?= $basePath ?? '' ?>/faq" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">FAQ</a>
        <a href="<?= $basePath ?? '' ?>/register" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">Register</a>
        <a href="<?= $basePath ?? '' ?>/login" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">Login</a>
        <a href="<?= $basePath ?? '' ?>/cart" class="py-2 px-2 rounded text-slate-700 hover:bg-slate-100">Cart<?= $cartCount > 0 ? ' (' . ($cartCount > 99 ? '99+' : $cartCount) . ')' : '' ?></a>
    </nav>
</header>

<main class="bg-slate-50">
