<?php
    if (!isset($_GET['order_number'])) {
        echo '<p>Tady nemáš co dělat :(</p>';
        exit();
    }

    $order_number = $_GET['order_number'];
?>

<div class="order_confirmed_wrapper">
    <div class="order_confirmed_wrap">
        <h2>Děkujeme za tvojí objednávku, usilovně na ní pracujeme, ať je u tebe co nejdřív!</h2>
        <h3>Číslo tvé objednávky: <a class="profile_link" href="index.php?page=order_detail&order_number=<?=$order_number?>"><?=$order_number?></a></h3>
        <p>Shrnutí objednávky najdeš na <a class="profile_link" href="index.php?page=user_details">svém profilu</a> nebo ve své mailové schránce <?= $_SESSION['email']?></p>
    </div>
</div>

