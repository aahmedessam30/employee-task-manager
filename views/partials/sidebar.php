<nav class="sidebar">
    <h4>My Sidebar</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="<?= route('dashboard') ?>">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= route('departments.index') ?>">Departments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= route('employees.index') ?>">Employees</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= route('auth.logout') ?>">Logout</a>
        </li>
    </ul>
</nav>
