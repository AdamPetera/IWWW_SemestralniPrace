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
                if (isset($_POST['variants'])) {
                    $variant_id = ProductVariantsController::getVariantIdByNameAndProductId($_POST['variants'], $product_id);
                    CartHasProductsController::insertOrUpdate($cart_id, $variant_id, $quantity);
                }
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
        var_dump($v);
        $trimmed_v = trim($v, "Obnovit");
        if (strpos($k, 'quantity') !== false && is_numeric($trimmed_v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int) $trimmed_v;
            if (is_numeric($id) && $quantity > 0) {
                CartHasProductsController::updateQuantity($cart_id, (int) $id, $quantity);
            }
        }
    }
}

$products = CartHasProductsController::getAllCartProductIds($cart_id);
$subtotal = 0.00;

if ($products) {
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$product['quantity'];
    }
}

if (isset($_POST['placeorder'])) {
    if ($subtotal != 0) {
        echo '<script type="text/javascript">
                window.location = "index.php?page=payment&subtotal='.$subtotal.'"
              </script>';
    } else {
        $error_message = 'Nelze přejít na platbu s prázdným košíkem :(';
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
                                <img src="<?=$product['image']?>" width="50" height="50" alt="<?=$product['p.name']?>">
                            </a>
                        </td>
                        <td>
                            <a href="index.php?page=product&id=<?=$product['product_id']?>"><?=$product['pname']?></a>
                            <br>
                            <p class="var_name">Varianta: <?=$product['name']?></p>
                            <a href="index.php?page=cart&remove=<?=$product['variant_id']?>" class="remove">Odstranit</a>
                        </td>
                        <td class="price"><?=$product['price']?> Kč</td>
                        <td class="quantity">
                            <input type="number" name="quantity-<?=$product['variant_id']?>" value="<?=$product['quantity']?>" min="1" placeholder="Počet" required>
                        </td>
                        <td class="price"><?=$product['price'] * $product['quantity']?> Kč</td>
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
                <input type="submit" value="Přejít na platbu" name="placeorder">
            </div>
            <?php if (isset($error_message)): ?>
                <div class="error_message">
                    <span class="error_msg"><?php echo $error_message; ?></span>
                </div>
            <?php endif ?>
        </form>
    </div>
</div>