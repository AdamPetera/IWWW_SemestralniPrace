<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Platba</title>
    <link rel="stylesheet" href="../styles/payment.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
    if (!isset($_SESSION["email"])) {
        include "login_form.php";
        exit();
    }
    $subtotal = 0.00;

    if (isset($_GET['subtotal'])) {
        if (is_numeric($_GET['subtotal']) && !empty($_GET['subtotal'])) {
            $subtotal = $_GET['subtotal'];
            if (isset($_POST['update'])) {
                $subtotal += (int) $_POST['payment_method'];
                $subtotal += (int) $_POST['delivery_method'];
            }
        } else {
            echo '<p style="text-align: center">Něco se mi na celkové ceně nezdá</p>';
            exit();
        }
    } else {
        echo '<p>Tady nemáš co dělat :(</p>';
        exit();
    }
?>

<div class="payment_wrapper">
    <form class="payment_wrap" method="post">
        <div class="form_wrapper">
            <div class="payment_delivery_method">
                <p class="row_name">Platební metoda</p>
                <select name="payment_method">
                    <option value="19">Dobírka (+19 Kč)</option>
                    <option value="0">Online kartou (+0 Kč)</option>
                    <option value="5">Internetové bankovnictví (+5 Kč)</option>
                </select>
                <p class="row_name">Způsob doručení</p>
                <select name="delivery_method">
                    <option value="0">Vyzvednutí na prodejně (+0 Kč)</option>
                    <option value="60">Balík do ruky (Česká Pošta, +60 Kč)</option>
                    <option value="40">Balík na poštu (+40 Kč)</option>
                    <option value="35">Zásilkovna (+35 Kč)</option>
                </select>
            </div>
            <div class="delivery_info_user">
                <div class="row">
                    <p class="row_name">Jméno</p>
                    <input type="text" name="firstname" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Příjmení</p>
                    <input type="text" name="lastname" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Telefon</p>
                    <input type="tel" name="phone" value="<?= isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"
                           pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}" required>
                </div>
                <div class="row">
                    <p class="row_name">Email</p>
                    <input type="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                </div>
            </div>
            <div class="delivery_info_address">
                <div class="row">
                    <p class="row_name">Ulice</p>
                    <input type="text" name="street" value="<?= isset($_POST['street']) ? $_POST['street'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Číslo popisné</p>
                    <input type="text" name="no" value="<?= isset($_POST['no']) ? $_POST['no'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Obec</p>
                    <input type="text" name="city" value="<?= isset($_POST['city']) ? $_POST['city'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">PSČ</p>
                    <input type="number" name="zipcode" value="<?= isset($_POST['zipcode']) ? $_POST['zipcode'] : ''; ?>" required>
                </div>
            </div>
        </div>
        <div class="subtotal">
            <span class="text">Celková cena</span>
            <span class="price"><?=$subtotal?></span> Kč
        </div>
        <div class="buttons">
            <input type="submit" value="Zpět do košíku" name="backtocart">
            <input type="submit" value="Obnovit" name="update">
            <input type="submit" value="Potvrdit a zaplatit" name="placeorder">
        </div>
    </form>
</div>
