<?php
    if (!isset($_SESSION["email"])) {
        include "login_form.php";
        exit();
    }
    $subtotal = 0.00;
    if (isset($_POST['backtocart'])) {
        echo '<script type="text/javascript">
                window.location = "index.php?page=cart"
                </script>';
    }

    $address = AddressController::getUsersAddress($_SESSION['row']['user_id']);

    if (isset($_GET['subtotal'])) {
        if (is_numeric($_GET['subtotal']) && !empty($_GET['subtotal'])) {
            $subtotal = $_GET['subtotal'];
            if (isset($_POST['update'])) {
                $subtotal += (int) $_POST['payment_method'];
                $subtotal += (int) $_POST['delivery_method'];
            }
            if (isset($_POST['placeorder'])) {
                $validation = OrderController::userInfoAndAddressValidation($_POST['firstname'], $_POST['lastname'], $_POST['email'],
                    $_POST['phone'], $_POST['street'], $_POST['no'], $_POST['city'], $_POST['zipcode']);
                if (count($validation) == 0) {
                    $conn = Connection::getPdoInstance();
                    $subtotal += (int) $_POST['payment_method'];
                    $subtotal += (int) $_POST['delivery_method'];

                    $today = date("Ymd");
                    $rand = strtoupper(substr(uniqid(sha1(time())),0,8));
                    $order_number = $today . $rand;
                    OrderController::insertOrder($conn, $_SESSION['row']['user_id'], $subtotal, $order_number);
                    OrderHasProductsController::insert($conn->lastInsertId(), $_SESSION['row']['user_id']);
                    echo '<script type="text/javascript">
                            window.location = "index.php?page=order_confirmed&order_number='.$order_number.'"
                            </script>';
                } else {
                    print_r($validation);
                }
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
                    <input type="text" name="firstname" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['firstname'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Příjmení</p>
                    <input type="text" name="lastname" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['lastname'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Telefon</p>
                    <input type="tel" name="phone" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['phone'] : ''; ?>"
                           pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}" required>
                </div>
                <div class="row">
                    <p class="row_name">Email</p>
                    <input type="email" name="email" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['email'] : ''; ?>" required>
                </div>
            </div>
            <div class="delivery_info_address">
                <div class="row">
                    <p class="row_name">Ulice</p>
                    <input type="text" name="street" value="<?= isset($address['row']) ? $address['row']['street'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Číslo popisné</p>
                    <input type="text" name="no" value="<?= isset($address['row']) ? $address['row']['no'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">Obec</p>
                    <input type="text" name="city" value="<?= isset($address['row']) ? $address['row']['city'] : ''; ?>" required>
                </div>
                <div class="row">
                    <p class="row_name">PSČ</p>
                    <input type="number" name="zipcode" value="<?= isset($address['row']) ? $address['row']['zipcode'] : ''; ?>" required>
                </div>
            </div>
        </div>
        <div class="subtotal">
            <span class="text">Celková cena</span>
            <span class="price"><?=$subtotal?></span> Kč
        </div>
        <div class="buttons">
            <input type="submit" value="Obnovit" name="update">
            <input type="submit" value="Potvrdit a zaplatit" name="placeorder">
        </div>
    </form>
    <form class="cart_button" method="post">
        <div class="button">
            <input type="submit" value="Zpět do košíku" name="backtocart">
        </div>
    </form>
</div>
