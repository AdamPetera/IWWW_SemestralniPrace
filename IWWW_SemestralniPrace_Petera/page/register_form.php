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
            include "../sidemenu_transform.php";
            ?>
            <div class="register_form_wrap">
                <div class="register_form">
                    <h1>Registrace</h1>
                    <form method="post">
                        <div class="txt_field">
                            <input type="text" required>
                            <span></span>
                            <label>Jméno</label>
                        </div>
                        <div class="txt_field">
                            <input type="text" required>
                            <span></span>
                            <label>Příjmení</label>
                        </div>
                        <div class="txt_field">
                            <input type="email" required>
                            <span></span>
                            <label>Email</label>
                        </div>
                        <div class="txt_field">
                            <input type="tel" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}" required>
                            <span></span>
                            <label>Telefonní číslo</label>
                        </div>
                        <div class="txt_field">
                            <input type="password" required>
                            <span></span>
                            <label>Heslo</label>
                        </div>
                        <input type="submit" value="Registrovat se">
                        <div class="login_link">Již máte účet? <a href="login_form.php">Přejít na přihlášení</a></div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
