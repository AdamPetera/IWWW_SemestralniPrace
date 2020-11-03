<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Uživatelské detaily</title>
    <link rel="stylesheet" href="../styles/user_details.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
    if (!isset($_SESSION["email"])) {
        include "login_form.php";
        exit();
    }

if (isset($_POST["updateProfile"])) {
    $conn = Connection::getPdoInstance();
    $result = UserController::emailExistsReturnArray($conn, $_SESSION["email"]);
    $row = $result["row"];

    if (isset($_POST["firstname"])) {
        if (!empty($_POST["firstname"])) {
            $firstname = $_POST["firstname"];
        } else {
            $firstname = $row["firstname"];
        }
    }
    if (isset($_POST["lastname"])) {
        if (!empty($_POST["lastname"])) {
            $lastname = $_POST["lastname"];
        } else {
            $lastname = $row["lastname"];
        }
    }
    if (isset($_POST["email"])) {
        $emailRowCount = UserController::emailExists($conn, $_POST["email"]);
        if (!empty($_POST["email"])) {
            $email = $_POST["email"];
        } else {
            $email = $row["email"];
        }
    }
    if (isset($_POST["phone"])) {
        if (!empty($_POST["phone"])) {
            $phone = $_POST["phone"];
        } else {
            $phone = $row["phone"];
        }
    }
    if (isset($_POST["password"])) {
        if (!empty($_POST["password"])) {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        } else {
            $password = $row["password"];
        }
    }

    if ($emailRowCount > 0) {
        $error_message = "Tento nový email již někdo používá!";
    } else {
        try {
            UserController::updateUser($conn, $email, $firstname, $lastname, $email, $phone);
        } catch (PDOException $e) {
            $error_message = "Něco se pokazilo :(";
        }
    }
}



if (isset($_POST["logOut"])) {
    session_destroy();
    session_unset();
    unset($_SESSION["email"]);
    $_SESSION = array();
    echo '<script type="text/javascript">
                    window.location = "index.php"
                    </script>';
}
?>
<div class="user_datails_wrap">
    <div class="user_info">
        <?php
            $row = $_SESSION["row"];
            echo $row["firstname"] . '<br>';
            echo $row["lastname"] . '<br>';
            echo $row["email"] . '<br>';
            echo $row["phone"] . '<br>';
        ?>
    </div>
    <div class="edit_profile_form_wrap">
        <div class="edit_profile_form">
            <h1>Úprava uživatelských údajů</h1>
            <form method="post">
                <div class="txt_field">
                    <input type="text" name="firstname">
                    <span></span>
                    <label>Jméno</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="lastname">
                    <span></span>
                    <label>Příjmení</label>
                </div>
                <div class="txt_field">
                    <input type="email" name="email">
                    <span></span>
                    <label>Email</label>
                </div>
                <div class="txt_field">
                    <input type="tel" name="phone" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}">
                    <span></span>
                    <label>Telefonní číslo</label>
                </div>
                <div class="txt_field">
                    <input type="password" name="password">
                    <span></span>
                    <label>Heslo</label>
                </div>
                <input type="submit" name="updateProfile" value="Uložit změny">
            </form>
        </div>
        <?php if (isset($error_message)): ?>
            <div class="form_error">
                <span class="error"><?php echo $error_message; ?></span>
            </div>
        <?php endif ?>
    </div>
    <form class="logout_button_wrap" method="post">
        <input class="logOut_button" type="submit" name="logOut" value="Odhlásit se">
    </form>
</div>



