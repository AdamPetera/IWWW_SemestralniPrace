<?php


class CartHasProductsController
{
    static function insertOrUpdate($conn, $cart_id, $product_id, $quantity)
    {
        $stmt = $conn->prepare("SELECT * FROM cart_has_products WHERE cart_id = :cart_id AND product_id = :product_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO cart_has_products (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");

            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE cart_has_products SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND product_id = :product_id");

            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        }

    }

    static function updateQuantity($conn, $cart_id, $product_id, $quantity)
    {
        $stmt = $conn->prepare("SELECT * FROM cart_has_products WHERE cart_id = :cart_id AND product_id = :product_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        $stmt = $conn->prepare("UPDATE cart_has_products SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);

        $stmt->execute();

    }

    static function deleteProductFromCart($conn, $cart_id, $product_id)
    {
        $stmt = $conn->prepare("DELETE FROM cart_has_products WHERE cart_id = :cart_id AND product_id = :product_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();
    }

    static function getAllCartProductIds($conn, $cart_id)
    {
        $stmt = $conn->prepare("SELECT * FROM cart_has_products WHERE cart_id = :cart_id");

        $stmt->bindParam(':cart_id', $cart_id);

        $stmt->execute();

        $products_in_cart = $stmt->fetchAll();
        $product_ids = array();
        foreach ($products_in_cart as $pic) {
            array_push($product_ids, (int)$pic['product_id']);
        }

        return $product_ids;
    }
}