<?php include views_path('layouts/header.php'); ?>

<?php include views_path('layouts/sidebar.php'); ?>

<?php include views_path('partials/alerts.php'); ?>

<!-- create employee form -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Add Employee</h1>
            <form action="<?= route('employees.store') ?>" method="POST">

                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= old('name') ?>">
                    <?php if (has_error('name')): ?>
                        <small class="text-danger"><?= get_error('name') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>">
                    <?php if (has_error('email')): ?>
                        <small class="text-danger"><?= get_error('email') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control">
                        <option value="">Select Department</option>
                        <?php if (isset($departments) && is_array($departments) && count($departments) > 0): ?>
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?= $department->id ?>" <?= old('department_id') === $department->id ? 'selected' : '' ?>><?= $department->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (has_error('department_id')): ?>
                        <small class="text-danger"><?= get_error('department_id') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <?php if (has_error('password')): ?>
                        <small class="text-danger"><?= get_error('password') ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include views_path('layouts/footer.php'); ?>
