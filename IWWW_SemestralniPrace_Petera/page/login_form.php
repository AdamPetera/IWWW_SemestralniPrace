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
    $validation = array();

    if (empty($_POST["email"])) {
        $validation["email"] = "Email musí být vyplněn";
    }
    if (empty($_POST["password"])) {
        $validation["password"] = "Heslo musí být vyplněné";
    }

    if (count($validation) == 0) {
        $conn = Connection::getPdoInstance();

        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT *, r.name FROM user u
                                            JOIN user_has_role ur ON u.user_id = ur.user_id
                                            LEFT JOIN role r ON r.role_id = ur.role_id
                                            WHERE u.email = :email
                                            AND u.password = :password");

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        $role = $stmt->fetchColumn(10);

        if ($stmt->rowCount() == 0) {
            $error_message = "Omlouváme se, ale zadané údaje nesouhlasí";
        } else {
            $succ_message = "Přihlášení proběhlo úspěšně";
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
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
    <?php if (isset($succ_message)): ?>
        <div class="successful">
            <span class="message"><?php echo $succ_message; ?></span>
        </div>
    <?php endif ?>
</div>
