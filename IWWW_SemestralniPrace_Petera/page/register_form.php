<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/sidemenu.css">
    <link rel="stylesheet" href="../styles/sidemenu_transfrom.css">
    <link rel="stylesheet" href="../styles/register_form.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <nav>
        <div class="logo"><a href="../index.php">SALIBANDYSTORE.CZ</a></div>
        <ul class="nav_links">
            <li><a href="../index.php">Domů</a></li>
            <li>
                <a href="#">Kontakt</a>
                <ul>
                    <li><a href="#">Formulář</a></li>
                    <li><a href="#">Obecné info</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Účet</a>
                <ul>
                    <li><a href="login_form.php">Přihlášení</a></li>
                    <li><a href="register_form.php">Registrace</a></li>
                </ul>
            </li>
            <li><a href="#">Košík</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <?php
    include "../sidemenu.php"
    ?>
    <div class="main">
        <div class="content">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $db = "web";

            if ($_POST) {

                $validation = array();
                if(empty($_POST["firstname"])) {
                    $validation["firstname"] = "Jméno musí být vyplněné";
                }
                if(empty($_POST["lastname"])) {
                    $validation["lastname"] = "Příjmení musí být vyplněné";
                }
                if(empty($_POST["email"])) {
                    $validation["email"] = "Email musí být vyplněn";
                }
                if(empty($_POST["phone"])) {
                    $validation["phone"] = "Telefonní číslo musí být vyplněné";
                }
                if(empty($_POST["password"])) {
                    $validation["password"] = "Heslo musí být vyplněné";
                }

                    if (count($validation) == 0) {
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
                            // set the PDO error mode to exception
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            // echo "Connected successfully";

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

                                if($chk->rowCount() > 0) {
                                    $email_error = "Omlouváme se, ale zadaný email již někdo používá";
                                } else {
                                    $stmt->execute();
                                    $register_confirmed = "Registrace proběhla úspěšně";
                                }

                            } catch(PDOException $e) {
                                echo "<br>" . $e->getMessage();
                            }
                        } catch(PDOException $e) {
                            echo "Connection failed: " . $e->getMessage();
                        }
                    }

            }
            ?>

            <?php
            include "../sidemenu_transform.php";
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
                        <div class="login_link">Již máte účet? <a href="login_form.php">Přejít na přihlášení</a></div>
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
            </div>

        </div>
    </div>
</div>

</body>
</html>
