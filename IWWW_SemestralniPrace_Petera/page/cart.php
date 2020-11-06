<?php
$conn = Connection::getPdoInstance();
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $product = ProductController::getProductById($conn, $product_id);

    if ($product && $quantity > 0) {
        if (isset($_SESSION["cart"]) && is_array($_SESSION["cart"])) {
            if (array_key_exists($product_id, $_SESSION["cart"])) {
                $_SESSION["cart"][$product_id] += $quantity;
            } else {
                $_SESSION["cart"][$product_id] = $quantity;
            }
        } else {
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }

    //exit;

} else {
    die("Produkt nenalzene :(");
}

if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
}

$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;

if ($products_in_cart) {
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
}
?>

<h2>Košík</h2>
