<?php include views_path('layouts/header.php'); ?>

<?php include views_path('layouts/sidebar.php'); ?>

<?php include views_path('partials/alerts.php'); ?>

<?php $employee = $employee ?? null; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Employee</h1>
            <form action="<?= route('employees.update', ['id' => $employee->id]) ?>" method="POST">

                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $employee->first_name ?>">
                    <?php if (has_error('first_name')): ?>
                        <small class="text-danger"><?= get_error('first_name') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $employee->last_name ?>">
                    <?php if (has_error('last_name')): ?>
                        <small class="text-danger"><?= get_error('last_name') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $employee->email ?>">
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
                                <option value="<?= $department->id ?>" <?= $employee->department_id === $department->id ? 'selected' : '' ?>><?= $department->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include views_path('layouts/footer.php'); ?>
