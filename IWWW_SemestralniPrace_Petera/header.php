<header>
    <nav>
        <div class="logo"><a href="index.php">SALIBANDYSTORE.CZ</a></div>
        <ul class="nav_links">
            <li><a href="index.php">Domů</a></li>
            <li>
                <a href="#">Kontakt</a>
                <ul>
                    <li><a href="#">Formulář</a></li>
                    <li><a href="#">Obecné info</a></li>
                </ul>
            </li>
            <li>
                <a href="index.php?page=user_details">Účet</a>
                <?php
                if(isset($_SESSION["logedIn"])) {
                    if (!$_SESSION["logedIn"]) {
                ?>
                    <ul>
                        <li><a href="index.php?page=login_form">Přihlášení</a></li>
                        <li><a href="index.php?page=register_form">Registrace</a></li>
                    </ul>
                <?php
                    }
                }
                ?>
            </li>
            <li><a href="index.php?page=basket">Košík</a></li>
        </ul>
    </nav>
</header>
