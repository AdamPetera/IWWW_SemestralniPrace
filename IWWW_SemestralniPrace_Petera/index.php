<?php
    function __autoload($class) {
        require_once './classes/' . $class . '.php';
    }
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>SalibandyStore</title>
    <?php
    include "stylesheets.php"
    ?>
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
    <div class="main" id="main">
        <div class="content">
            <?php
                include "sidemenu_transform.php";
                if (isset($_GET["page"])) {
                    $pathToFile = "./page/" . $_GET["page"] . ".php";
                } else {
                    $pathToFile = "./page/main.php";
                }
                if (file_exists($pathToFile)) {
                    include $pathToFile;
                }
            ?>
        </div>
    </div>
</div>

</body>
</html>