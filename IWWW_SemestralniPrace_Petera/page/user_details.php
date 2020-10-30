<?php
    if ($_SESSION["logedIn"] == false) {
        include "login_form.php";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/user_details.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<div class="details_wrap">
    <h2>Uživatelské detaily</h2>
</div>
