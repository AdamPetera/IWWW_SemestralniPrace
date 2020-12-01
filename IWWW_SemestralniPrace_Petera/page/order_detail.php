<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail objednávky</title>
    <link rel="stylesheet" href="../styles/order_detail.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

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

?>

<div class="order_detail_wrapper">
    <div class="order_detail_wrap">
        <h2>Detail objednávky číslo <?=$order['order_number']?></h2>
        <p>Datum objednávky: <?=$order['order_date']?></p>
        <p>Celková cena objednávky: <?=$order['price']?> Kč</p>
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
                        <?$image = ProductImageController::getProductImage($product_id, 'main');?>
                        <tr>
                            <td class="img">
                                <a href="index.php?page=product&id=<?=$product_id?>">
                                    <img src="<?=$image?>" width="50" height="50" alt="<?=$product['name']?>">
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
