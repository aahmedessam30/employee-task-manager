<?= $this->extend('layouts.master') ?>

<?= $this->section('content') ?>

<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Departments</div>
            <div class="card-body">
                <h5 class="card-title text-center">0</h5>
                <p class="card-text text-center">Total Departments</p>
            </div>

            <div class="card-footer text-center">
                <a href="<?= route('departments.index') ?>" class="btn btn-light">View</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Employees</div>
            <div class="card-body">
                <h5 class="card-title text-center">0</h5>
                <p class="card-text text-center">Total Employees</p>
            </div>

            <div class="card-footer text-center">
                <a href="<?= route('employees.index') ?>" class="btn btn-light">View</a>
            </div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Tasks Assigned</div>
            <div class="card-body">
                <h5 class="card-title text-center">0</h5>
                <p class="card-text text-center">Total Tasks</p>
            </div>

            <div class="card-footer text-center">
                <a href="<?= route('tasks.index') ?>" class="btn btn-light">View</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Completed Tasks</div>
            <div class="card-body">
                <h5 class="card-title text-center">0</h5>
                <p class="card-text text-center">Total Completed Tasks</p>
            </div>

            <div class="card-footer text-center">
                <a href="<?= route('tasks.index') ?>" class="btn btn-light">View</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content') ?>

