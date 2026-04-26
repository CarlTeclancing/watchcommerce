<?php $title = 'Users'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">User Management</h1>
                <p class="text-slate-600">Manage system users and roles</p>
            </div>
            <a href="<?= $basePath ?? '' ?>/admin" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                <i class="fas fa-arrow-left"></i>Back
            </a>
        </div>

        <!-- Role Assignment Form -->
        <div class="surface-card p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-user-lock text-pink-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Assign Roles</h2>
            </div>
            <form method="POST" action="<?= $basePath ?? '' ?>/admin/users/role" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>">
                <input class="form-control flex-1" name="user_id" placeholder="User ID" type="number" required>
                <select name="role_id" class="form-control md:w-48">
                    <option value="">Select role...</option>
                    <option value="1">Customer</option>
                    <option value="2">Inventory Manager</option>
                    <option value="3">Content Manager</option>
                    <option value="4">Administrator</option>
                </select>
                <button type="submit" class="btn-primary px-6 py-3 inline-flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-check"></i>Assign
                </button>
            </form>
        </div>

        <!-- Users List -->
        <div class="surface-card p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-users text-pink-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Users (<?= count($users) ?>)</h2>
            </div>
            <div class="overflow-x-auto -mx-8 px-8">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-slate-200 bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">ID</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Name</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">Email</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-900">2FA Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-b border-slate-100 hover:bg-pink-50 transition">
                            <td class="px-4 py-4 font-medium text-slate-900"><?= htmlspecialchars($user['id'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 font-medium text-slate-900"><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 text-slate-600 text-xs"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4">
                                <?php if ($user['two_factor_enabled'] ?? 0): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle"></i>Enabled
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-times-circle"></i>Disabled
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($users)): ?>
            <p class="text-center text-slate-500 py-12"><i class="fas fa-inbox text-3xl mb-3 block opacity-30"></i>No users found</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
