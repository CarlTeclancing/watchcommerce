<?php $title = 'CMS'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Content Management</h1>
                <p class="text-slate-600">Edit site pages and content</p>
            </div>
            <a href="<?= $basePath ?? '' ?>/admin" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                <i class="fas fa-arrow-left"></i>Back
            </a>
        </div>

        <!-- Pages List -->
        <div class="surface-card p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-file-edit text-purple-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Pages</h2>
            </div>
            <div class="space-y-3">
                <?php foreach ($pages as $page): ?>
                <div class="border border-slate-200 rounded-lg p-4 hover:border-purple-300 hover:bg-purple-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-900"><?= htmlspecialchars($page['title'] ?? '', ENT_QUOTES) ?></h3>
                            <p class="text-xs text-slate-500 mt-1"><i class="fas fa-link text-xs mr-1"></i><?= htmlspecialchars($page['slug'] ?? '', ENT_QUOTES) ?></p>
                            <p class="mt-2">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold <?= ($page['status'] ?? 'draft') === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                    <?= htmlspecialchars($page['status'] ?? 'draft', ENT_QUOTES) ?>
                                </span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?= $basePath ?? '' ?>/admin/cms?edit=<?= (int)($page['id'] ?? 0) ?>" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-medium">
                                <i class="fas fa-pen text-xs"></i>Edit
                            </a>
                            <a href="<?= $basePath ?? '' ?>/<?= htmlspecialchars($page['slug'] ?? '', ENT_QUOTES) ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition text-sm font-medium">
                                <i class="fas fa-external-link-alt text-xs"></i>View
                            </a>
                            <form method="POST" action="<?= $basePath ?? '' ?>/admin/cms/page/delete" class="inline" onsubmit="return confirm('Delete this page permanently?');">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>">
                                <input type="hidden" name="page_id" value="<?= (int)($page['id'] ?? 0) ?>">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">
                                    <i class="fas fa-trash text-xs"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Create/Edit Form -->
        <div class="surface-card p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-plus-circle text-slate-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900"><?= isset($editPage) && $editPage ? 'Edit Page' : 'Create Page' ?></h2>
            </div>
            <form method="POST" action="<?= $basePath ?? '' ?>/admin/cms/page" class="space-y-4">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>">
                <input type="hidden" name="page_id" value="<?= (int)($editPage['id'] ?? 0) ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Page Slug</label>
                    <input class="form-control" name="slug" placeholder="e.g., about-us" value="<?= htmlspecialchars($editPage['slug'] ?? '', ENT_QUOTES) ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Page Title</label>
                    <input class="form-control" name="title" placeholder="Page title" value="<?= htmlspecialchars($editPage['title'] ?? '', ENT_QUOTES) ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Content (HTML allowed)</label>
                    <textarea class="form-control" name="body_html" placeholder="Enter page content..." rows="8" required><?= htmlspecialchars($editPage['body_html'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Status</label>
                    <select name="status" class="form-control max-w-xs">
                        <option value="draft" <?= (($editPage['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= (($editPage['status'] ?? '') === 'published') ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary px-6 py-3 inline-flex items-center gap-2">
                        <i class="fas fa-save"></i><?= isset($editPage) && $editPage ? 'Update Page' : 'Save Page' ?>
                    </button>
                    <?php if (isset($editPage) && $editPage): ?>
                    <a href="<?= $basePath ?? '' ?>/admin/cms" class="btn-secondary px-6 py-3 inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>Create New
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
