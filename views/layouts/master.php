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
        padding: 1rem;
    }
    .sidebar .nav-link {
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }
    .content {
        margin-left: 250px;
        padding: 0;
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
