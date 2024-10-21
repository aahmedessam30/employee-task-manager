<?php

if (session()->has('errors')) {
    $errors = session()->get('errors');

    if (is_array($errors)) {
        echo "<div class='alert alert-danger'><ul style='margin: 0; list-style: none; padding-left: 1rem;'>";

        foreach ($errors as $error) {
            foreach ($error as $message) {
                echo "<li>$message</li>";
            }
        }

        echo "</ul></div>";
    } else {
        echo "<div class='alert alert-danger'>$errors</div>";
    }
}

if (session()->has('success')) {
    echo "<div class='alert alert-success'>" . session()->get('success') . "</div>";
}

if (session()->has('error')) {
    echo "<div class='alert alert-danger'>" . session()->get('error') . "</div>";
}
