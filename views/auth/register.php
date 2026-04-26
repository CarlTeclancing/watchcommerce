<?php $title = 'Register'; include __DIR__ . '/../layouts/topbar.php'; ?>
<div class="surface-card mx-auto max-w-md p-6 md:p-8">
    <h1 class="text-2xl font-bold">Create Account</h1>
    <p class="mt-2 text-sm text-slate-600">Create your account to track orders and save your watch preferences.</p>
    <form class="mt-4 space-y-4" method="POST" action="<?= $basePath ?? '' ?>/register">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
        <input class="form-control" name="name" placeholder="Name" required>
        <input class="form-control" name="email" type="email" placeholder="Email" required>
        <input class="form-control" name="password" type="password" placeholder="Password" required>
        <button type="submit" class="btn-primary w-full px-4 py-2">Register</button>
    </form>
    <p class="mt-4 text-sm text-slate-600">Already have an account? <a href="<?= $basePath ?? '' ?>/login" class="font-semibold text-amber-700 hover:text-amber-600">Login here</a>.</p>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
