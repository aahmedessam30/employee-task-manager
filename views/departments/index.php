<?php $this->extend('layouts.master'); ?>

<?php $this->section('content'); ?>

<h2>Departments</h2>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= route('departments.create') ?>" class="btn btn-primary">Create Department</a>
    <form method="get" action="<?= route('departments.index') ?>">
        <input type="search" name="search" class="form-control" placeholder="Search..."
               value="<?= request()->get('search') ?>">
    </form>
</div>

<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Employees</th>
        <th>Salary</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($departments) && is_array($departments->items()) && count($departments->items()) > 0): ?>
        <?php $loop = 1; ?>
        <?php foreach ($departments->items() as $department): ?>
            <tr>
                <td><?= $loop++ ?></td>
                <td><?= $department->name ?></td>
                <td><?= $department->employees_count ?></td>
                <td><?= $department->employees_salary ?></td>
                <td>
                    <a href="<?= route('departments.edit', ['id' => $department->id]) ?>"
                       class="btn btn-sm btn-warning">Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" id="delete-<?= $department->id ?>">Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">No departments found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php if (isset($departments) && is_array($departments) && count($departments) > 0): ?>
    <?= $departments->links() ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[id^=delete-]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (confirm('Are you sure you want to delete this department?')) {
                    fetch('<?= route('departments.destroy', ['id' => '']) ?>' + button.id.split('-')[1], {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                        }
                    }).then(function (response) {
                        if (response.ok) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    });
</script>

<?php $this->endSection('content'); ?>
