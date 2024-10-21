<?php include views_path('layouts/header.php'); ?>

<h2 class="mb-4">Register</h2>

<form method="post" action="<?= route('auth.store') ?>">
    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

    <?php $errors = session()->get('errors'); ?>

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errors['name'])) echo "<span class='text-danger'>{$errors['name'][0]}</span>"; ?>
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username">
        <?php if (isset($errors['username'])) echo "<span class='text-danger'>{$errors['username'][0]}</span>"; ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email">
        <?php if (isset($errors['email'])) echo "<span class='text-danger'>{$errors['email'][0]}</span>"; ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
        <?php if (isset($errors['password'])) echo "<span class='text-danger'>{$errors['password'][0]}</span>"; ?>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" id="password_confirmation" name="password_confirmation">
        <?php if (isset($errors['password_confirmation'])) echo "<span class='text-danger'>{$errors['password_confirmation'][0]}</span>"; ?>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" id="terms" name="terms" class="form-check-input <?= isset($errors['terms']) ? 'is-invalid' : '' ?>">
        <label class="form-check-label" for="terms">I agree to the terms and conditions</label>
        <?php if (isset($errors['terms'])) echo "<span class='text-danger'>{$errors['terms'][0]}</span>"; ?>
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>

<?php include views_path('layouts/footer.php'); ?>
