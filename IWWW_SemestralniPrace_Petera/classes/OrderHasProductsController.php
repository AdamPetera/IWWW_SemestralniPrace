<?php


class OrderHasProductsController
{
    static function insert($order_id, $user_id) {
        $conn = Connection::getPdoInstance();
        $cart_id = (int) CartController::getCartId($conn, $user_id);
        $cart_products = CartHasProductsController::getAllCartProductIds($conn, $cart_id);

        foreach ($cart_products as $id => $quantity) {
            $product_price = (int) ProductController::getProductPrice($id);
            $stmt = $conn->prepare("INSERT INTO order_has_products (order_id, product_id, price, quantity)
                                    VALUES (:order_id, :product_id, :price, :quantity)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $id);
            $stmt->bindParam(':price', $product_price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        }

        CartHasProductsController::deleteAllCartProducts($cart_id);
    }

    static function getAllOrderProductIds($order_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM order_has_products WHERE order_id = :order_id");

        $stmt->bindParam(':order_id', $order_id);

        $stmt->execute();

        $order_products = $stmt->fetchAll();
        $product_ids_and_keys = array();
        foreach ($order_products as $op) {
            $product_ids_and_keys[(int)$op['product_id']] = (int) $op['quantity'];
        }

        return $product_ids_and_keys;
    }

    static function getAllOrderProducts($order_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM order_has_products WHERE order_id = :order_id");

        $stmt->bindParam(':order_id', $order_id);

        $stmt->execute();

        $order_products = $stmt->fetchAll();
        $product_ids_and_prices = array();
        foreach ($order_products as $op) {
            $product_ids_and_prices[(int)$op['product_id']] = (int) $op['price'];
        }

        return $product_ids_and_prices;
    }

    static function removeAllProductFromOrder($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM order_has_products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
}