<nav class="sidebar bg-light" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1)">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= request()->fullUrl() == route('dashboard') ? 'active' : '' ?>"
               href="<?= route('dashboard') ?>">Dashboard</a>
        </li>
        <?php if (auth()->user()->isNotEmployee()) : ?>
            <li class="nav-item">
                <a class="nav-link <?= request()->is('departments/*') ? 'active' : '' ?>"
                   href="<?= route('departments.index') ?>">Departments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= request()->is('employees/*') ? 'active' : '' ?>"
                   href="<?= route('employees.index') ?>">Employees</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link <?= request()->is('tasks/*') ? 'active' : '' ?>"
               href="<?= route('tasks.index') ?>">Tasks</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= route('auth.logout') ?>">Logout</a>
        </li>
    </ul>
</nav>
