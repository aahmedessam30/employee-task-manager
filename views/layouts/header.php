<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 200px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 1rem;
            overflow-y: auto;
        }

        .content {
            margin-left: 220px;
            padding: 2rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
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
