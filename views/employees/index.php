<?php $this->extend('layouts.master'); ?>

<?php $this->section('content'); ?>

<div class="row">
    <div class="col-md-12">
        <h1>Employees</h1>
        <a href="<?= route('employees.create') ?>" class="btn btn-primary">Add Employee</a>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($employees) && is_array($employees->items()) && count($employees->items()) > 0): ?>
                <?php $loop = 1; ?>
                <?php foreach ($employees->items() as $employee) : ?>
                    <tr>
                        <td><?= $loop ?></td>
                        <td><?= $employee->full_name ?></td>
                        <td><?= $employee->email ?></td>
                        <td><?= $employee->department_name ?? '----' ?></td>
                        <td>
                            <a href="<?= route('employees.edit', ['id' => $employee->id]) ?>"
                               class="btn btn-warning">Edit</a>
                            <form action="<?= route('employees.destroy', ['id' => $employee->id]) ?>" method="POST"
                                  style="display: inline-block">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this employee?')">Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No employees found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (isset($employees) && is_array($employees) && count($employees) > 0): ?>
            <?= $employees->links() ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection('content'); ?>
