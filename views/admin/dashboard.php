<?php $title = 'Admin Dashboard'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Admin Dashboard</h1>
            <p class="text-slate-600">Monitor and manage your luxury watch commerce platform</p>
        </div>
        
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php foreach ($kpis as $key => $value): ?>
            <div class="surface-card p-6 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-xs uppercase tracking-wide font-semibold"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $key)), ENT_QUOTES) ?></p>
                        <p class="text-4xl font-bold text-slate-900 mt-3"><?= htmlspecialchars((string)$value, ENT_QUOTES) ?></p>
                    </div>
                    <div class="text-4xl text-amber-600 opacity-20"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Quick Access Navigation -->
        <div class="surface-card p-8 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-slate-900">Quick Access</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="<?= $basePath ?? '' ?>/admin/inventory" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-boxes text-amber-600 text-xl group-hover:text-amber-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Manage Inventory</div>
                        <div class="text-xs text-slate-500">View and edit watches</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/orders" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-receipt text-blue-600 text-xl group-hover:text-blue-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">View Orders</div>
                        <div class="text-xs text-slate-500">Track customer orders</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/sell-pipeline" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-dollar-sign text-green-600 text-xl group-hover:text-green-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Sell Pipeline</div>
                        <div class="text-xs text-slate-500">Manage valuations</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/cms" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-file-edit text-purple-600 text-xl group-hover:text-purple-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Manage Content</div>
                        <div class="text-xs text-slate-500">Edit site pages</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/users" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-users text-pink-600 text-xl group-hover:text-pink-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Manage Users</div>
                        <div class="text-xs text-slate-500">Assign roles</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/reports/export" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-chart-bar text-indigo-600 text-xl group-hover:text-indigo-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Export Reports</div>
                        <div class="text-xs text-slate-500">Download analytics</div>
                    </div>
                </a>
                <a href="<?= $basePath ?? '' ?>/admin/settings" class="btn-secondary px-6 py-4 flex items-center gap-3 group">
                    <i class="fas fa-cogs text-teal-600 text-xl group-hover:text-teal-700"></i>
                    <div class="text-left">
                        <div class="font-semibold text-slate-900">Platform Settings</div>
                        <div class="text-xs text-slate-500">Branding, contact, and UI settings</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div class="flex items-center gap-4">
            <form method="POST" action="<?= $basePath ?? '' ?>/logout" class="inline">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="px-6 py-3 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 font-medium transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
