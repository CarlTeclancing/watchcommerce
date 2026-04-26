<?php $title = $post['title']; include __DIR__ . '/../layouts/topbar.php'; ?>
<article class="rounded-xl bg-white p-8 shadow">
    <h1 class="text-3xl font-bold"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="mt-2 text-slate-500"><?= htmlspecialchars((string)$post['excerpt'], ENT_QUOTES, 'UTF-8') ?></p>
    <div class="prose mt-6 max-w-none text-slate-700"><?= $post['body_html'] ?></div>
</article>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
