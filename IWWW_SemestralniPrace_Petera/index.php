<?php
    function __autoload($class) {
        require_once './classes/' . $class . '.php';
    }
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

//    if (isset($_POST["email"])) {
//        if ($_POST["email"] != "") {
//            //$_SESSION["login"] = true;
//        }
//    }
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>SalibandyStore</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/sidemenu.css">
    <link rel="stylesheet" href="styles/sidemenu_transfrom.css">
    <link rel="stylesheet" href="styles/slider_show.css">
    <link rel="stylesheet" href="styles/login_form.css">
    <link rel="stylesheet" href="styles/register_form.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/shopitems_sticks.css">
    <link rel="stylesheet" href="styles/shopitems.css">
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/user_details.css">
    <link rel="stylesheet" href="styles/product.css">
    <link rel="stylesheet" href="styles/cart.css">
    <link rel="stylesheet" href="styles/add_product.css">
    <link rel="stylesheet" href="styles/payment.css">
    <link rel="stylesheet" href="styles/order_confirmed.css">
    <link rel="stylesheet" href="styles/order_detail.css">
    <link rel="stylesheet" href="styles/edit_product.css">
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