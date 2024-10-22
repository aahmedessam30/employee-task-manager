<?php $this->extend('layouts/master'); ?>

<?php $employee = $employee ?? null; ?>

<?php $this->section('content'); ?>

<div class="row">
    <div class="col-md-12">
        <h1>Edit Employee</h1>

        <!-- Image -->
        <div class="mb-3">
            <img src="<?= asset('images/employees/' . $employee->image) ?>" alt="<?= $employee->first_name . ' ' . $employee->last_name ?>" class="img-thumbnail" style="width: 100px;">
        </div>

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
                <label for="salary">Salary</label>
                <input type="number" name="salary" id="salary" class="form-control" value="<?= $employee->salary ?>">
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
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection('content'); ?>
