<?php include views_path('layouts/header.php'); ?>

<div class="container my-4">

    <h2 class="mb-4">Register</h2>

    <?php include views_path('partials/alerts.php') ?>

    <form method="post" action="<?= route('auth.store') ?>">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">

        <?php $errors = session()->get('errors'); ?>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name"
                   class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['name'])) echo "<span class='text-danger'>{$errors['name'][0]}</span>"; ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email"
                   name="email">
            <?php if (isset($errors['email'])) echo "<span class='text-danger'>{$errors['email'][0]}</span>"; ?>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                   id="password" name="password">
            <?php if (isset($errors['password'])) echo "<span class='text-danger'>{$errors['password'][0]}</span>"; ?>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password"
                   class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>"
                   id="password_confirmation" name="password_confirmation">
            <?php if (isset($errors['password_confirmation'])) echo "<span class='text-danger'>{$errors['password_confirmation'][0]}</span>"; ?>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" id="role" name="role">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
            </select>
            <?php if (isset($errors['role'])) echo "<span class='text-danger'>{$errors['role'][0]}</span>"; ?>
        </div>

        <div class="mb-3 d-none" id="department_div">
            <label for="department" class="form-label">Department</label>
            <select class="form-select <?= isset($errors['department']) ? 'is-invalid' : '' ?>" id="department"
                    name="department_id">
                <option value="">Select Department</option>
                <?php if (isset($departments) && is_array($departments) && count($departments) > 0): ?>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department->id ?>"><?= $department->name ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if (isset($errors['department_id'])) echo "<span class='text-danger'>{$errors['department_id'][0]}</span>"; ?>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

</div>

<?php include views_path('layouts/footer.php'); ?>

<script>
    document.getElementById('role').addEventListener('change', function () {
        let role = this.value;
        let departmentDiv = document.getElementById('department_div');
        let departmentSelect = document.getElementById('department_id');

        if (role === 'employee') {
            departmentDiv.classList.remove('d-none');
            departmentSelect.setAttribute('required', 'required');
        } else {
            departmentDiv.classList.add('d-none');
            departmentSelect.removeAttribute('required');
            departmentSelect.value = '';
        }
    });
</script>
