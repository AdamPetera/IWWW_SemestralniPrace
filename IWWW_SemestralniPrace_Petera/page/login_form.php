<?php
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $validation = UserController::loginUserValidation($_POST["email"], $_POST["password"]);

    if (count($validation) == 0) {
        $row = UserController::loginUser($_POST["email"]);

        if (!$row) {
            $error_message = "Omlouváme se, ale zadané údaje nesouhlasí";
        } else {
            $password = $row["password"];
            if (password_verify($_POST["password"], $password)) {
                $role = $row["name"];
                $email = $row["email"];
                $_SESSION["email"] = $email;
                $_SESSION["role"] = $role;
                $_SESSION["row"] = $row;
                echo '<script type="text/javascript">
                    window.location = "index.php"
                    </script>';
            } else {
                $error_message = "Omlouváme se, ale zadané údaje nesouhlasí";
            }
        }
    }
    else {
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
