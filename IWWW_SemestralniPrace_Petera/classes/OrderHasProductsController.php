<?php


class OrderHasProductsController
{
    static function insert($order_id, $user_id) {
        $conn = Connection::getPdoInstance();
        $cart_id = (int) CartController::getCartId($conn, $user_id);
        $cart_products = CartHasProductsController::getAllCartProductIds($cart_id);

        foreach ($cart_products as $product) {
            $product_price = (int) ProductController::getProductPrice($product['product_id']);
            $variant_id = (int) $product['variant_id'];
            $quantity = (double) $product['quantity'];
            $stmt = $conn->prepare("INSERT INTO order_has_products (order_id, variant_id, price, quantity)
                                    VALUES (:order_id, :variant_id, :price, :quantity)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':variant_id', $variant_id);
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
        $stmt = $conn->prepare("SELECT o.order_id order_id, o.price order_price, o.quantity order_quantity, p.product_id product_id, p.name product_name, pv.name pv_name FROM order_has_products o
                                            LEFT JOIN product_variants pv ON o.variant_id = pv.variant_id
                                            LEFT JOIN product p ON p.product_id = pv.product_id
                                            WHERE order_id = :order_id");

        $stmt->bindParam(':order_id', $order_id);

        $stmt->execute();

        return $stmt->fetchAll();

    }

    static function removeAllProductFromOrder($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM order_has_products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
}