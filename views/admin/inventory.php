<?php $title = 'Manage Inventory'; include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-8">

        <!-- Page header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-1">Inventory Management</h1>
                <p class="text-slate-600">Manage your watch collection</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('addWatchModal').classList.remove('hidden')"
                        class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold">
                    <i class="fas fa-plus"></i> Add Watch
                </button>
                <a href="<?= $basePath ?? '' ?>/admin" class="btn-secondary inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Watches List -->
        <div class="surface-card p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-boxes text-amber-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-900">Watches in Stock <span class="text-lg font-normal text-slate-500">(<?= count($items) ?>)</span></h2>
            </div>
            <div class="overflow-x-auto -mx-8 px-8">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-slate-200 bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-4 font-semibold text-slate-700">Title</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-700">Brand</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-700">Price</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-700">Status</th>
                            <th class="text-left px-4 py-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr class="border-b border-slate-100 hover:bg-amber-50 transition">
                            <td class="px-4 py-4 font-medium text-slate-900"><?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 text-slate-600 capitalize"><?= htmlspecialchars($item['brand_slug'] ?? '', ENT_QUOTES) ?></td>
                            <td class="px-4 py-4 font-semibold text-amber-600">$<?= number_format($item['list_price'] ?? 0, 2) ?></td>
                            <td class="px-4 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                    <?= ($item['status'] ?? '') === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                    <?= htmlspecialchars($item['status'] ?? '', ENT_QUOTES) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            class="btn-secondary px-3 py-1.5 text-xs"
                                            data-watch-id="<?= (int)($item['id'] ?? 0) ?>"
                                            data-title="<?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES) ?>"
                                            data-brand="<?= htmlspecialchars($item['brand_slug'] ?? '', ENT_QUOTES) ?>"
                                            data-reference="<?= htmlspecialchars($item['reference_number'] ?? '', ENT_QUOTES) ?>"
                                            data-condition="<?= htmlspecialchars($item['condition_grade'] ?? 'very_good', ENT_QUOTES) ?>"
                                            data-year="<?= htmlspecialchars((string)($item['year_of_production'] ?? ''), ENT_QUOTES) ?>"
                                            data-price="<?= htmlspecialchars((string)($item['list_price'] ?? ''), ENT_QUOTES) ?>"
                                            data-status="<?= htmlspecialchars($item['status'] ?? 'draft', ENT_QUOTES) ?>"
                                            onclick="openEditWatchModal(this)">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>

                                    <form method="POST" action="<?= $basePath ?? '' ?>/admin/inventory/delete"
                                          onsubmit="return confirm('Delete this watch? If linked to orders/cart, it will be archived instead.');"
                                          class="inline">
                                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="watch_id" value="<?= (int)($item['id'] ?? 0) ?>">
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>

                                    <a href="<?= $basePath ?? '' ?>/watches/<?= htmlspecialchars($item['slug'] ?? '', ENT_QUOTES) ?>"
                                       class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-700 font-medium text-xs">
                                        <i class="fas fa-external-link-alt text-xs"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($items)): ?>
            <div class="text-center py-16">
                <i class="fas fa-inbox text-5xl text-slate-300 mb-4 block"></i>
                <p class="text-slate-500 font-medium">No watches in inventory</p>
                <p class="text-slate-400 text-sm mt-1">Click "Add Watch" to get started.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ============================================================
     ADD WATCH MODAL
     ============================================================ -->
<div id="addWatchModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)this.classList.add('hidden')">

    <div class="surface-card w-full max-w-2xl p-0 overflow-hidden" style="max-height:90vh;overflow-y:auto;">

        <!-- Modal header -->
        <div class="flex items-center justify-between px-8 py-5 border-b border-slate-200"
             style="background:linear-gradient(135deg,#f3c54a,#d4af37);">
            <div class="flex items-center gap-3">
                <i class="fas fa-plus-circle text-slate-900 text-xl"></i>
                <h2 class="text-xl font-bold text-slate-900">Add New Watch</h2>
            </div>
            <button onclick="document.getElementById('addWatchModal').classList.add('hidden')"
                    class="text-slate-700 hover:text-slate-900 text-xl leading-none">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal body -->
        <div class="px-8 py-7">
            <form method="POST" action="<?= $basePath ?? '' ?>/admin/inventory"
                  enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input class="form-control" name="title"
                               placeholder="e.g. Rolex Submariner 126610LN" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Brand <span class="text-red-500">*</span>
                            <span class="font-normal text-slate-400">(slug format)</span>
                        </label>
                        <input class="form-control" name="brand_slug"
                               placeholder="e.g. rolex, patek-philippe" required>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Reference Number</label>
                        <input class="form-control" name="reference_number"
                               placeholder="e.g. 126610LN">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            List Price (USD) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 font-semibold text-sm">$</span>
                            <input class="form-control pl-7" name="list_price"
                                   type="number" step="0.01" min="0"
                                   placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Year of Production</label>
                        <input class="form-control" name="year_of_production"
                               type="number" min="1900" max="2099" placeholder="e.g. 2022">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Condition Grade</label>
                        <select class="form-control" name="condition_grade">
                            <option value="new">New / Unworn</option>
                            <option value="excellent">Excellent</option>
                            <option value="very_good" selected>Very Good</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select class="form-control" name="status">
                            <option value="draft" selected>Draft</option>
                            <option value="published">Published</option>
                            <option value="reserved">Reserved</option>
                            <option value="sold">Sold</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4 — Hero image upload -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-image text-amber-500 mr-1"></i>
                        Hero / Cover Image
                    </label>
                    <div id="heroDropzone"
                         class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 cursor-pointer hover:border-amber-400 hover:bg-amber-50 transition"
                         onclick="document.getElementById('heroFileInput').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400" id="heroIcon"></i>
                        <span class="text-sm text-slate-500" id="heroLabel">Click to upload or drag & drop</span>
                        <span class="text-xs text-slate-400">JPG, PNG, WEBP — max 5 MB</span>
                        <img id="heroPreview" src="" alt="" class="hidden mt-2 h-32 w-full object-cover rounded-lg">
                    </div>
                    <input id="heroFileInput" type="file" name="hero_image" accept="image/*"
                           class="hidden"
                           onchange="previewHero(this)">
                </div>

                <!-- Row 5 — Gallery upload -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-images text-amber-500 mr-1"></i>
                        Gallery Images
                        <span class="font-normal text-slate-400">(select multiple)</span>
                    </label>
                    <div id="galleryDropzone"
                         class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 cursor-pointer hover:border-amber-400 hover:bg-amber-50 transition"
                         onclick="document.getElementById('galleryFileInput').click()">
                        <i class="fas fa-layer-group text-3xl text-slate-400"></i>
                        <span class="text-sm text-slate-500" id="galleryLabel">Click to select gallery photos</span>
                        <span class="text-xs text-slate-400">JPG, PNG, WEBP — up to 10 images</span>
                    </div>
                    <div id="galleryPreviews" class="mt-3 grid grid-cols-4 gap-2 hidden"></div>
                    <input id="galleryFileInput" type="file" name="gallery_images[]" accept="image/*"
                           multiple class="hidden"
                           onchange="previewGallery(this)">
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button"
                            onclick="document.getElementById('addWatchModal').classList.add('hidden')"
                            class="btn-secondary inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit"
                            class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold">
                        <i class="fas fa-plus"></i> Add Watch to Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     EDIT WATCH MODAL
     ============================================================ -->
<div id="editWatchModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(15,23,42,0.72);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)this.classList.add('hidden')">

    <div class="surface-card w-full max-w-2xl p-0 overflow-hidden" style="max-height:90vh;overflow-y:auto;">
        <div class="flex items-center justify-between px-8 py-5 border-b border-slate-200"
             style="background:linear-gradient(135deg,#f3c54a,#d4af37);">
            <div class="flex items-center gap-3">
                <i class="fas fa-pen text-slate-900 text-xl"></i>
                <h2 class="text-xl font-bold text-slate-900">Edit Watch</h2>
            </div>
            <button onclick="document.getElementById('editWatchModal').classList.add('hidden')"
                    class="text-slate-700 hover:text-slate-900 text-xl leading-none">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="px-8 py-7">
            <form method="POST" action="<?= $basePath ?? '' ?>/admin/inventory/update" class="space-y-4">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="watch_id" id="edit_watch_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Title</label>
                        <input class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Brand Slug</label>
                        <input class="form-control" id="edit_brand_slug" name="brand_slug" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Reference Number</label>
                        <input class="form-control" id="edit_reference_number" name="reference_number">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">List Price (USD)</label>
                        <input class="form-control" id="edit_list_price" name="list_price" type="number" min="0" step="0.01" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Year of Production</label>
                        <input class="form-control" id="edit_year_of_production" name="year_of_production" type="number" min="1900" max="2099">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Condition Grade</label>
                        <select class="form-control" id="edit_condition_grade" name="condition_grade">
                            <option value="new">New / Unworn</option>
                            <option value="excellent">Excellent</option>
                            <option value="very_good">Very Good</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="reserved">Reserved</option>
                            <option value="sold">Sold</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button"
                            onclick="document.getElementById('editWatchModal').classList.add('hidden')"
                            class="btn-secondary inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewHero(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('heroPreview').src = e.target.result;
        document.getElementById('heroPreview').classList.remove('hidden');
        document.getElementById('heroIcon').classList.add('hidden');
        document.getElementById('heroLabel').textContent = input.files[0].name;
    };
    reader.readAsDataURL(input.files[0]);
}
function previewGallery(input) {
    const container = document.getElementById('galleryPreviews');
    container.innerHTML = '';
    if (!input.files || !input.files.length) { container.classList.add('hidden'); return; }
    container.classList.remove('hidden');
    document.getElementById('galleryLabel').textContent = input.files.length + ' image(s) selected';
    Array.from(input.files).slice(0, 10).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'h-20 w-full object-cover rounded-lg border border-slate-200';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
// Close modal on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('addWatchModal').classList.add('hidden');
        document.getElementById('editWatchModal').classList.add('hidden');
    }
});

function openEditWatchModal(button) {
    document.getElementById('edit_watch_id').value = button.dataset.watchId || '';
    document.getElementById('edit_title').value = button.dataset.title || '';
    document.getElementById('edit_brand_slug').value = button.dataset.brand || '';
    document.getElementById('edit_reference_number').value = button.dataset.reference || '';
    document.getElementById('edit_list_price').value = button.dataset.price || '';
    document.getElementById('edit_year_of_production').value = button.dataset.year || '';
    document.getElementById('edit_condition_grade').value = button.dataset.condition || 'very_good';
    document.getElementById('edit_status').value = button.dataset.status || 'draft';
    document.getElementById('editWatchModal').classList.remove('hidden');
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
