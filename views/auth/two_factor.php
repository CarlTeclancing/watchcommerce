<?php $title = 'Two Factor Verification'; include __DIR__ . '/../layouts/topbar.php'; ?>
<div class="surface-card mx-auto max-w-md p-6 md:p-8">
    <h1 class="text-2xl font-bold">Two-Factor Verification</h1>
    <p class="mt-2 text-sm text-slate-600">Enter your 6-digit admin verification code.</p>
    <form class="mt-4 space-y-4" method="POST" action="<?= $basePath ?? '' ?>/2fa">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
        <input class="form-control" name="code" maxlength="6" placeholder="123456" required>
        <button type="submit" class="btn-primary w-full px-4 py-2">Verify</button>
    </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
