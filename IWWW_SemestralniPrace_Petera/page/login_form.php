<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login_form.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if ($_POST) {

    $validation = UserController::loginUserValidation($_POST["email"], $_POST["password"]);

    if (count($validation) == 0) {
        $conn = Connection::getPdoInstance();
        $stmt = UserController::loginUser($conn, $_POST["email"]);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $password = $row["password"];
        $role = $row["name"];
        $email = $row["email"];

        if ($stmt->rowCount() == 0) {
            $error_message = "Omlouváme se, ale zadané údaje nesouhlasí";
        } else if (password_verify($_POST["password"], $password)) {
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            $_SESSION["row"] = $row;
            echo '<script type="text/javascript">
                    window.location = "index.php"
                    </script>';
        }
    } else {
        $error_message = "Musíte zadat všechny údaje";
    }
}
?>
<div class="login_form_wrap">
    <div class="login_form">
        <h1>Přihlášení</h1>
        <form method="post">
            <div class="txt_field">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label>Heslo</label>
            </div>
            <div class="forget_password">Zapomněli jste heslo?</div>
            <input type="submit" name="login" value="Přihlásit se">
            <div class="signup_link">Nejste registrovaní? <a href="index.php?page=register_form">Registrovat se</a></div>
        </form>
    </div>
    <?php if (isset($error_message)): ?>
        <div class="form_error">
            <span class="error"><?php echo $error_message; ?></span>
        </div>
    <?php endif ?>

</div>
