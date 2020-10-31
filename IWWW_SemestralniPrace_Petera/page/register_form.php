<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/register_form.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if ($_POST) {

    $validation = array();
    if (empty($_POST["firstname"])) {
        $validation["firstname"] = "Jméno musí být vyplněné";
    }
    if (empty($_POST["lastname"])) {
        $validation["lastname"] = "Příjmení musí být vyplněné";
    }
    if (empty($_POST["email"])) {
        $validation["email"] = "Email musí být vyplněn";
    }
    if (empty($_POST["phone"])) {
        $validation["phone"] = "Telefonní číslo musí být vyplněné";
    }
    if (empty($_POST["password"])) {
        $validation["password"] = "Heslo musí být vyplněné";
    }

    if (count($validation) == 0) {

        $conn = Connection::getPdoInstance();

        try {
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $password = $_POST["password"];

            $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, phone, password)
                                                        VALUES (:firstname, :lastname, :email, :phone, :password)");

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $password);

            $chk = $conn->prepare("SELECT email FROM user WHERE email = :email");
            $chk->bindParam(':email', $email);

            $chk->execute();

            if ($chk->rowCount() > 0) {
                $email_error = "Omlouváme se, ale zadaný email již někdo používá";
            } else {
                $stmt->execute();
                $register_confirmed = "Registrace proběhla úspěšně";
            }

        } catch (PDOException $e) {
            echo "<br>" . $e->getMessage();
        }

    }

}
?>
<div class="register_form_wrap">
    <div class="register_form">
        <h1>Registrace</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="firstname" required>
                <span></span>
                <label>Jméno</label>
            </div>
            <div class="txt_field">
                <input type="text" name="lastname" required>
                <span></span>
                <label>Příjmení</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="tel" name="phone" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}" required>
                <span></span>
                <label>Telefonní číslo</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label>Heslo</label>
            </div>
            <input type="submit" value="Registrovat se">
            <div class="login_link">Již máte účet? <a href="index.php?page=login_form">Přejít na přihlášení</a></div>
        </form>
    </div>
    <?php if (isset($email_error)): ?>
        <div class="form_error">
            <span class="error"><?php echo $email_error; ?></span>
        </div>
    <?php endif ?>
    <?php if (isset($register_confirmed)): ?>
        <div class="successful">
            <div class="succ_mess">
                <span class="message"><?php echo $register_confirmed; ?></span>
                <button><a href="login_form.php">Přihlásit se</a></button>
            </div>
        </div>
    <?php endif ?>
</div>
