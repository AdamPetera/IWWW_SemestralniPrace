<header>
    <nav>
        <div class="logo"><a href="index.php">SALIBANDYSTORE.CZ</a></div>
        <ul class="nav_links">
            <li><a href="index.php">Domů</a></li>
            <li>
                <a href="#">Kontakt</a>
                <ul>
                    <li><a href="index.php?page=contact">Formulář</a></li>
                </ul>
            </li>
            <li>
                <a href="index.php?page=user_details">Účet</a>
                <?php
                    if (!isset($_SESSION["email"])) {
                ?>
                    <ul>
                        <li><a href="index.php?page=login_form">Přihlášení</a></li>
                        <li><a href="index.php?page=register_form">Registrace</a></li>
                    </ul>
                <?php
                    } else {
                ?>
                        <ul>
                            <li><a href="index.php?page=log_out">Odhlásit se</a></li>
                        </ul>
                <?php
                    }
                ?>
            </li>
            <li><a href="index.php?page=cart">Košík</a></li>
            <?php
            if (isset($_SESSION["role"])) {
                if ($_SESSION["role"] == "admin") {
            ?>
            <li><a href="index.php?page=manage_users">Správa uživatelů</a></li>
                <?php
                }
            }
            ?>
            <?php
            if (isset($_SESSION["role"])) {
                if ($_SESSION["role"] == "seller" || $_SESSION["role"] == "admin") {
                    ?>
                    <li><a href="index.php?page=manage_orders">Správa objednávek</a></li>
                    <?php
                }
            }
            ?>
        </ul>
    </nav>
</header>
