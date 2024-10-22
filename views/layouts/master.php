<!DOCTYPE html>
<html lang="en">

<?php include views_path('partials/head.php'); ?>

<style>
    body {
        overflow-x: hidden;
    }
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 250px;
        background-color: #f8f9fa;
        padding: 0;
    }
    .sidebar h4 {
        text-align: center;
        padding-top: 10px;
        margin: 0;
        color: #333;
    }
    .sidebar .nav {
        padding-top: 70px;
    }
    .sidebar .nav-link {
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
        color: #333;
    }
    .sidebar .nav-link.active {
        background-color: #e9ecef;
    }
    .content {
        margin-left: 250px;
        padding-top: 60px;
    }
</style>

<body>

<?php include views_path('partials/sidebar.php'); ?>

<div class="content">

    <?php include views_path('partials/header.php'); ?>

    <div class="container my-4">

        <?= $this->include('partials.alerts') ?>

        @yield('content')

    </div>

    <?php include views_path('partials/footer.php'); ?>
</div>

</body>

</html>
