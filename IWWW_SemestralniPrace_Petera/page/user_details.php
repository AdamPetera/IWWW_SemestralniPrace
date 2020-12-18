<?php
    if (!isset($_SESSION["email"])) {
        include "login_form.php";
        exit();
    }

$conn = Connection::getPdoInstance();
$result = UserController::emailExistsReturnArray($conn, $_SESSION["email"]);
$row = $result["row"];
$addressAndRowcount = AddressController::getUsersAddress($row['user_id']);
$addressRowCount = $addressAndRowcount['rowCount'];
$address = $addressAndRowcount['row'];
$orders = OrderController::getAllUsersOrders($_SESSION['row']['user_id']);


if (isset($_POST["updateProfile"])) {
    $variableArray = UserController::setVariables($_POST, $row);

    if ($variableArray['emailRowCount'] > 0) {
        $error_message_profile = "Tento nový email již někdo používá!";
    } else {
        try {
            UserController::updateUser($conn, $variableArray['email'], $variableArray['firstname'],
                $variableArray['lastname'], $variableArray['email'], $variableArray['phone'],
                $variableArray['password']);
            $updated = UserController::getUserById($row['user_id']);
            $_SESSION["row"] = $updated;
            $success_message_profile = "Uživatelské údaje změněny :)";
        } catch (PDOException $e) {
            $error_message_profile = "Něco se pokazilo :(";
        }
    }
}

if (isset($_POST['updateAddress'])) {
    $userAddress = AddressController::getAddressByUserId($row['user_id']);
    $variableArray = AddressController::setVariables($_POST, $userAddress);
    try {
        $addressRowCount = AddressController::updateAddress($variableArray['street'], $variableArray['no'],
            $variableArray['city'], $variableArray['zipcode'], $row['user_id']);
        if ($addressRowCount != 0) {
            $success_message_address = "Adresa byla uložena :)";
        } else {
            $error_message_address = "Něco se pokazilo :(";
        }
    } catch (PDOException $e) {
        $error_message_address = "Něco se pokazilo :(";
    }
}
?>

<div class="user_datails_wrap">
    <div class="user_info">
        <h2>Vaše uživatelské údaje</h2>
        <div class="info">
            <?php
            $info = $_SESSION["row"];
                echo '<p>Jméno: '.$info['firstname'].'</p>';
                echo '<p>Příjmení: '.$info['lastname'].'</p>';
                echo '<p>Email: '.$info['email'].'</p>';
                echo '<p>Telefon: '.$info['phone'].'</p>';
            ?>
        </div>
        <div class="address">
            <?php
                if ($addressRowCount != 0) {
                    echo '<p>Ulice: '.$address['street'].'</p>';
                    echo '<p>Číslo popisné: '.$address['no'].'</p>';
                    echo '<p>Obec: '.$address['city'].'</p>';
                    echo '<p>PSČ: '.$address['zipcode'].'</p>';
                } else {
                    echo '<form action="index.php?page=add_address&id='. $_SESSION['row']['user_id'] .'" method="post" class="addressButton">
                            <input class="addAddressButton" type="submit" value="Přidat adresu">
                          </form>';
                }
            ?>
        </div>
    </div>
    <div class="orders_wrap">
        <h2>Vaše objednávky</h2>
        <form method="post">
            <table>
                <thead class="t_head">
                <tr>
                    <td>Číslo objednávky</td>
                    <td>Cena</td>
                    <td>Datum</td>
                    <td>Detail</td>
                </tr>
                </thead>
                <tbody class="t_body">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center">Nemáte zatím žádné objednávky</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="order_number">
                                <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>"><?=$order['order_number']?></a>
                            </td>
                            <td class="price"><?=$order['price']?> Kč</td>
                            <td class="order_date"><?=$order['order_date']?></td>
                            <td class="detail_button">
                                <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>">Detail objednávky</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
    <div class="edit_profile_form_wrap">
        <div class="edit_profile_form">
            <h1>Úprava uživatelských údajů</h1>
            <form method="post">
                <div class="txt_field">
                    <input type="text" name="firstname" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['firstname'] : ''; ?>">
                    <span></span>
                    <label>Jméno</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="lastname" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['lastname'] : ''; ?>">
                    <span></span>
                    <label>Příjmení</label>
                </div>
                <div class="txt_field">
                    <input type="email" name="email" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['email'] : ''; ?>">
                    <span></span>
                    <label>Email</label>
                </div>
                <div class="txt_field">
                    <input type="tel" name="phone" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['phone'] : ''; ?>" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}">
                    <span></span>
                    <label>Telefonní číslo</label>
                </div>
                <div class="txt_field">
                    <input type="password" name="password">
                    <span></span>
                    <label>Heslo</label>
                </div>
                <input type="submit" name="updateProfile" value="Uložit změny">
            </form>
            <?php if (isset($error_message_profile)): ?>
                <div class="error_message">
                    <span class="error_msg"><?php echo $error_message_profile; ?></span>
                </div>
            <?php endif ?>
            <?php if (isset($success_message_profile)): ?>
                <div class="success_message">
                    <span class="success_msg"><?php echo $success_message_profile; ?></span>
                </div>
            <?php endif ?>
        </div>
        <div class="edit_address_form">
            <h1>Úprava adresy</h1>
            <form method="post">
                <div class="txt_field">
                    <input type="text" name="street" value="<?= isset($address) ? $address['street'] : ''; ?>">
                    <span></span>
                    <label>Ulice</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="no" value="<?= isset($address) ? $address['no'] : ''; ?>">
                    <span></span>
                    <label>Číslo popisné</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="city" value="<?= isset($address) ? $address['city'] : ''; ?>">
                    <span></span>
                    <label>Obec</label>
                </div>
                <div class="txt_field">
                    <input type="number" name="zipcode"  value="<?= isset($address) ? $address['zipcode'] : ''; ?>"min="0">
                    <span></span>
                    <label>PSČ</label>
                </div>
                <input type="submit" name="updateAddress" value="Upravit adresu">
            </form>
            <?php if (isset($error_message_address)): ?>
                <div class="error_message">
                    <span class="error_msg"><?php echo $error_message_address; ?></span>
                </div>
            <?php endif ?>
            <?php if (isset($success_message_address)): ?>
                <div class="success_message">
                    <span class="success_msg"><?php echo $success_message_address; ?></span>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>



