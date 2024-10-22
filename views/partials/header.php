<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1)">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= route('home') ?>">Employee Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (session()->has('user')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= route('dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= route('auth.logout') ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= route('auth.login') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= route('auth.register') ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
