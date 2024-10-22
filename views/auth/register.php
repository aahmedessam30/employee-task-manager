<?= $this->extend('layouts.auth') ?>

<?= $this->section('content') ?>

<h2>Register</h2>

<form method="post" action="<?= route('auth.store') ?>">

    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" id="first_name" name="first_name"
               class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errors['first_name'])) echo "<span class='text-danger'>{$errors['first_name'][0]}</span>"; ?>
    </div>

    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" id="last_name" name="last_name"
               class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errors['last_name'])) echo "<span class='text-danger'>{$errors['last_name'][0]}</span>"; ?>
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

<script>
    document.getElementById('role').addEventListener('change', function () {
        let departmentDiv = document.getElementById('department_div');
        if (this.value === 'employee') {
            departmentDiv.classList.remove('d-none');
        } else {
            departmentDiv.classList.add('d-none');
        }
    });
</script>

<?= $this->endSection('content') ?>
