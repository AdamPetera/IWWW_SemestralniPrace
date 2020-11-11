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
    $product_ids_and_keys = OrderHasProductsController::getAllOrderProductIds($order['order_id']);

    if ($product_ids_and_keys) {
    $products = ProductController::getAllProductsByIds($product_ids_and_keys);
    }

    $order_products_prices = OrderHasProductsController::getAllOrderProducts($order['order_id']);

    if (isset($_POST['backtouserdetails'])) {
        echo '<script type="text/javascript">
                window.location = "index.php?page=user_details"
              </script>';
    }

?>

<div class="order_detail_wrapper">
    <div class="order_detail_wrap">
        <h2>Detail objednávky číslo <?=$_GET['order_number']?></h2>
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
                        <?$image = ProductImageController::getProductImage($product['product_id'], 'main');?>
                        <tr>
                            <td class="img">
                                <a href="index.php?page=product&id=<?=$product['product_id']?>">
                                    <img src="<?=$image?>" width="50" height="50" alt="<?=$product['name']?>">
                                </a>
                            </td>
                            <td>
                                <a href="index.php?page=product&id=<?=$product['product_id']?>"><?=$product['name']?></a>
                            </td>
                            <td class="price"><?=$order_products_prices[$product['product_id']]?> Kč</td>
                            <td class="quantity">
                                <input type="number" value="<?=$product_ids_and_keys[$product['product_id']]?>" readonly>
                            </td>
                            <td class="price"><?=$order_products_prices[$product['product_id']] * $product_ids_and_keys[$product['product_id']]?> Kč</td>
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
