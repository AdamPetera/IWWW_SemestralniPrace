<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login_form.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "web";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<div class="login_form_wrap">
    <div class="login_form">
        <h1>Přihlášení</h1>
        <form method="post">
            <div class="txt_field">
                <input type="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="password" required>
                <span></span>
                <label>Heslo</label>
            </div>
            <div class="forget_password">Zapomněli jste heslo?</div>
            <input type="submit" value="Přihlásit se">
            <div class="signup_link">Nejste registrovaní? <a href="register_form.php">Registrovat se</a></div>
        </form>
    </div>
</div>
