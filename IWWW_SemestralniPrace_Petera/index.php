<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>SalibandyStore</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/sidemenu.css">
    <link rel="stylesheet" href="styles/sidemenu_transfrom.css">
    <link rel="stylesheet" href="styles/slider_show.css">
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
include "header.php"
?>

<div class="container">
    <?php
    include "sidemenu.php"
    ?>
    <div class="main">
        <div class="content">
            <?php
                include "sidemenu_transform.php";
            ?>
            <div class="slider_show_wrap">
                <?php
                    include "slider_show.php";
                ?>
            </div>
            <h1>Welcome</h1>
            <p>Dont hesitate to get in touch!</p>
        </div>
    </div>
</div>

</body>
</html>