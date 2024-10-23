<!DOCTYPE html>
<html lang="en">

<?php include views_path('partials/head.php'); ?>

<body>

<?php include views_path('partials/header.php'); ?>

<div class="container mb-4" style="margin-top: 80px !important;">

    <?= $this->include('partials.alerts') ?>

    @yield('content')

</div>

<?php include views_path('partials/footer.php'); ?>

</body>

</html>
