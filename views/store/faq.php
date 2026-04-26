<?php $title = 'FAQ'; include __DIR__ . '/../layouts/topbar.php'; ?>
<h1 class="text-3xl font-bold">Frequently Asked Questions</h1>
<div class="mt-6 space-y-4">
    <?php foreach ($faqs as $faq): ?>
        <div class="rounded-xl bg-white p-5 shadow">
            <h2 class="font-semibold"><?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="mt-2 text-slate-600"><?= htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
