<?php include views_path('layouts/header.php'); ?>

<?php include views_path('partials/alerts.php') ?>

<div class="container my-4">

    <h2>Departments</h2>

    <form method="post" action="<?= route('departments.store') ?>">

        <input type="hidden" name="_token" value="<?= csrf_token() ?>">

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= old('name') ?>">
            <?php if (has_error('name')): ?>
                <small class="text-danger"><?= get_error('name') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"><?= old('description') ?></textarea>
            <?php if (has_error('description')): ?>
                <small class="text-danger"><?= get_error('description') ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Create Department</button>
    </form>
</div>

<?php include views_path('layouts/footer.php'); ?>
