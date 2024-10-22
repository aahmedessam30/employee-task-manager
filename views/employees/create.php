<?php include views_path('layouts/header.php'); ?>

<?php include views_path('layouts/sidebar.php'); ?>

<?php include views_path('partials/alerts.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Add Employee</h1>
            <form action="<?= route('employees.store') ?>" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                           value="<?= old('first_name') ?>">
                    <?php if (has_error('first_name')): ?>
                        <small class="text-danger"><?= get_error('first_name') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                           value="<?= old('last_name') ?>">
                    <?php if (has_error('last_name')): ?>
                        <small class="text-danger"><?= get_error('last_name') ?></small>
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
                    <label for="salary">Salary</label>
                    <input type="number" name="salary" id="salary" class="form-control" value="<?= old('salary') ?>">
                    <?php if (has_error('salary')): ?>
                        <small class="text-danger"><?= get_error('salary') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <?php if (has_error('image')): ?>
                        <small class="text-danger"><?= get_error('image') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include views_path('layouts/footer.php'); ?>
