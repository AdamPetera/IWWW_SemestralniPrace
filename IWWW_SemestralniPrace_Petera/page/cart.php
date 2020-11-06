<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Nákupní košík</title>
    <link rel="stylesheet" href="../styles/cart.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if (!isset($_SESSION["email"])) {
    include "login_form.php";
    exit();
}
$conn = Connection::getPdoInstance();
$cart_id = (int) CartController::getCartId($conn, $_SESSION['row']['user_id']);
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $product = ProductController::getProductById($conn, $product_id);

    if ($product && $quantity > 0) {
        if (isset($_POST['add_to_basket'])) {
            if (isset($_SESSION['email'])) {
                CartHasProductsController::insertOrUpdate($conn, $cart_id, $product['product_id'], $quantity);
            } else {
                include "login_form.php";
                exit();
            }
        }
    }
}

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $cart_id = (int) CartController::getCartId($conn, $_SESSION['row']['user_id']);
    CartHasProductsController::deleteProductFromCart($conn, $cart_id, $_GET['remove']);
}

if (isset($_POST['update'])) {
    foreach ($_POST as $k => $v) {
        echo $v;
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int) $v;
            if (is_numeric($id) && $quantity > 0) {
                CartHasProductsController::updateQuantity($conn, $cart_id, (int) $id, $quantity);
            }
        } else {
            //echo 'mimo';
        }
    }
}

$products_in_cart = CartHasProductsController::getAllCartProductIds($conn, $cart_id);
$product_ids = CartHasProductsController::getAllCartProductIds($conn, $cart_id);
$products = array();
$subtotal = 0.00;

if ($products_in_cart) {
    $array_to_question_marks = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $conn->prepare('SELECT * FROM product WHERE product_id IN (' . $array_to_question_marks . ')');
    $result = $stmt->execute(array_keys($product_ids));
    $products = $stmt->fetchAll();
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['product_id']];
    }
}
?>

<div class="cart_wrapper">
    <div class="cart">
        <h2>Váš nákupní košík</h2>
        <form method="post">
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
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center">Nemáte žádné produkty v nákupním košíku</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="img">
                            <a href="index.php?page=product&id=<?=$product['product_id']?>">
                                <img src="<?=$product['image']?>" width="50" height="50" alt="<?=$product['name']?>">
                            </a>
                        </td>
                        <td>
                            <a href="index.php?page=product&id=<?=$product['product_id']?>"><?=$product['name']?></a>
                            <br>
                            <a href="index.php?page=cart&remove=<?=$product['product_id']?>" class="remove">Odstranit</a>
                        </td>
                        <td class="price"><?=$product['price']?> Kč</td>
                        <td class="quantity">
                            <input type="number" name="quantity-<?=$product['product_id']?>" value="<?=$products_in_cart[$product['product_id']]?>" min="1"" placeholder="Počet" required>
                        </td>
                        <td class="price"><?=$product['price'] * $products_in_cart[$product['product_id']]?> Kč</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="subtotal">
                <span class="text">Celková cena</span>
                <span class="price"><?=$subtotal?></span> Kč
            </div>
            <div class="buttons">
                <input type="submit" value="Obnovit" name="update">
                <input type="submit" value="Závazně objednat" name="placeorder">
            </div>
        </form>
    </div>
</div>