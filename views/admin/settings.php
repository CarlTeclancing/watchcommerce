<?php $title = 'Settings'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Platform Settings</h1>
                <p class="text-slate-600">Configure branding, contact details, and storefront behavior.</p>
            </div>
            <a href="<?= $basePath ?? '' ?>/admin" class="btn-secondary inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form method="POST" action="<?= $basePath ?? '' ?>/admin/settings" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="surface-card p-8">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-brush text-amber-600 text-2xl"></i>
                    <h2 class="text-2xl font-bold text-slate-900">Branding</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Site Name</label>
                        <input class="form-control" name="site_name" value="<?= htmlspecialchars((string)($settings['site_name'] ?? 'WATCHES'), ENT_QUOTES) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Primary Currency Code</label>
                        <input class="form-control" name="currency_code" maxlength="3" value="<?= htmlspecialchars((string)($settings['currency_code'] ?? 'USD'), ENT_QUOTES) ?>" placeholder="USD">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Logo Upload</label>
                        <input class="form-control" type="file" name="logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                        <?php if (!empty($settings['logo_url'])): ?>
                        <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <img src="<?= htmlspecialchars((string)$settings['logo_url'], ENT_QUOTES) ?>" alt="Current logo" class="h-10 object-contain">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Favicon Upload</label>
                        <input class="form-control" type="file" name="favicon_file" accept="image/png,image/x-icon,image/vnd.microsoft.icon,image/svg+xml">
                        <?php if (!empty($settings['favicon_url'])): ?>
                        <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 inline-flex items-center gap-2">
                            <img src="<?= htmlspecialchars((string)$settings['favicon_url'], ENT_QUOTES) ?>" alt="Current favicon" class="h-6 w-6 object-contain">
                            <span class="text-xs text-slate-500">Current favicon</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="surface-card p-8">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-address-book text-blue-600 text-2xl"></i>
                    <h2 class="text-2xl font-bold text-slate-900">Contact & Footer</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Support Email</label>
                        <input class="form-control" type="email" name="support_email" value="<?= htmlspecialchars((string)($settings['support_email'] ?? ''), ENT_QUOTES) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Support Phone</label>
                        <input class="form-control" name="support_phone" value="<?= htmlspecialchars((string)($settings['support_phone'] ?? ''), ENT_QUOTES) ?>" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Footer Tagline</label>
                        <input class="form-control" name="footer_tagline" value="<?= htmlspecialchars((string)($settings['footer_tagline'] ?? ''), ENT_QUOTES) ?>">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Footer Copyright Text</label>
                        <input class="form-control" name="footer_copyright" value="<?= htmlspecialchars((string)($settings['footer_copyright'] ?? ''), ENT_QUOTES) ?>">
                    </div>
                </div>
            </div>

            <div class="surface-card p-8">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-sliders-h text-emerald-600 text-2xl"></i>
                    <h2 class="text-2xl font-bold text-slate-900">Storefront Controls</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Top Trust Badge Text</label>
                        <input class="form-control" name="hero_badge_text" value="<?= htmlspecialchars((string)($settings['hero_badge_text'] ?? ''), ENT_QUOTES) ?>">
                    </div>
                    <label class="inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="maintenance_mode" value="1" <?= (($settings['maintenance_mode'] ?? '0') === '1') ? 'checked' : '' ?>>
                        <span class="text-sm text-slate-700"><strong>Maintenance Mode</strong> (visual flag only)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="btn-primary inline-flex items-center gap-2 px-7 py-3 text-sm font-semibold">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
