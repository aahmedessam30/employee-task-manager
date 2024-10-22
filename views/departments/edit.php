<?php include views_path('layouts/header.php'); ?>

<?php include views_path('partials/alerts.php') ?>

<?php $department = $department ?? null; ?>

<div class="container my-4">

    <h2>Departments</h2>

    <form method="post" action="<?= route('departments.update', ['id' => $department->id]) ?>">

        <input type="hidden" name="_token" value=" <?= csrf_token() ?>">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= old('name', $department->name) ?>">
            <?php if (has_error('name')): ?>
                <small class="text-danger"><?= get_error('name') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description"
                      class="form-control"><?= old('description', $department->description) ?></textarea>
            <?php if (has_error('description')): ?>
                <small class="text-danger"><?= get_error('description') ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update Department</button>

        <a href="<?= route('departments.index') ?>" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include views_path('layouts/footer.php'); ?>
