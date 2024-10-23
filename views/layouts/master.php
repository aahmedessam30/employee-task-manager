<!DOCTYPE html>
<html lang="en">

<?php include views_path('partials/head.php'); ?>

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
