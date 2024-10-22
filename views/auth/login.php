<?php include views_path('layouts/header.php'); ?>

<div class="container my-4">

<h2>Login</h2>

<?php include views_path('partials/alerts.php') ?>

<form method="post" action="<?= route('auth.authenticate') ?>">
    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>

</div>

<?php include views_path('layouts/footer.php'); ?>
