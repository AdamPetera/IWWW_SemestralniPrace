<?php
    if (!isset($_GET['order_number'])) {
        echo '<p>Tady se mi něco nezdá</p>';
        exit();
    }

    $verification = OrderController::verificationOfUsersOrder($_GET['order_number'], $_SESSION['row']['user_id']);
    if (!$verification) {
        echo '<p>Můžeš si prohlížet jen objednávky, které ti patří!</p>';
        exit();
    }

    $order = OrderController::getOrderByOrderNumber($_GET['order_number']);
    $products = OrderHasProductsController::getAllOrderProducts($order['order_id']);

    if (isset($_POST['backtouserdetails'])) {
        echo '<script type="text/javascript">
                window.location = "index.php?page=user_details"
              </script>';
    }

    if (isset($_POST['change_state'])) {
        OrderController::updatePaidState($order['order_id'], $_POST['select_paid']);
        OrderController::updateOrderState($order['order_id'], $_POST['select_state']);
        echo '<script type="text/javascript">
                window.location = "index.php?page=order_detail&order_number='.$order['order_number'].'"
              </script>';
    }

?>

<div class="order_detail_wrapper">
    <div class="order_detail_wrap">
        <h2>Detail objednávky číslo <?=$order['order_number']?></h2>
        <div class="order_details">
            <div class="personal_info">
                <p>Datum objednávky: <?=$order['order_date']?></p>
                <p>Celková cena objednávky: <?=$order['price']?> Kč</p>
                <br>
                <p>Stav Vaší objednávky: <?=$order['human_readable']?></p>
                <p>Objednávka zaplacena: <?=(int) $order['paid'] == 0 ? "NE" : "ANO"?></p>
            </div>
            <div class="sent_to">
                <h4 class="sent_to_title">Adresa pro doručení:</h4>
                <p>Ulice: <?=$order['street']?></p>
                <p>Číslo popisné: <?=$order['no']?></p>
                <p>Obec: <?=$order['city']?></p>
                <p>PSČ: <?=$order['zipcode']?></p>
            </div>
        </div>
        <?php
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "seller") {
                ?>
                <form method="post">
                    <div class="selections_wrap">
                        <div class="selection">
                            <select name="select_state" required>
                                <?php
                                foreach (OrderStateRepository::getAllOrderStates() as $state) {
                                    echo '<option value="'.$state['human_readable'].'">'.$state['human_readable'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="selection">
                            <select name="select_paid" required>
                                <option value="O">NE</option>;
                                <option value="1">ANO</option>;
                            </select>
                        </div>
                    </div>

                    <div class="btn_change_state">
                        <input type="submit" value="Potvrdit změny v objednávce" name="change_state">
                    </div>
                </form>
                <?php
            }
        }
        ?>
        <div class="order_table">
            <h2>Produkty</h2>
            <table>
                <thead class="t_head">
                <tr>
                    <td colspan="2">Produkt</td>
                    <td>Cena</td>
                    <td>Počet</td>
                    <td>Celkově</td>
                </tr>
                </thead>
                <tbody class="t_body">
                    <?php foreach ($products as $product): ?>
                        <?$product_id = (int) $product['product_id']?>
                        <tr>
                            <td class="img">
                                <a href="index.php?page=product&id=<?=$product_id?>">
                                    <img src="<?=$product['image']?>" width="50" height="50" alt="<?=$product['name']?>">
                                </a>
                            </td>
                            <td>
                                <a href="index.php?page=product&id=<?=$product_id?>"><?=$product['product_name']?></a>
                                <p class="var_name"><?=$product['pv_name']?></p>
                            </td>
                            <td class="price"><?=(double)$product['order_price']?> Kč</td>
                            <td class="quantity">
                                <input type="number" value="<?=(int)$product['order_quantity']?>" readonly>
                            </td>
                            <td class="price"><?=(double)$product['order_price'] * (int)$product['order_quantity']?> Kč</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <form method="post">
            <div class="button">
                <input type="submit" value="Zpět na uživatelský účet" name="backtouserdetails">
            </div>
        </form>
    </div>
</div>
