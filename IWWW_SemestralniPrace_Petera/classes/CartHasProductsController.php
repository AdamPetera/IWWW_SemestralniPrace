<?php


class CartHasProductsController
{
    static function insertOrUpdate($cart_id, $variant_id, $quantity)
    {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM cart_has_products WHERE cart_id = :cart_id AND variant_id = :variant_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':variant_id', $variant_id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO cart_has_products (cart_id, variant_id, quantity) VALUES (:cart_id, :variant_id, :quantity)");

            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':variant_id', $variant_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE cart_has_products SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND variant_id = :variant_id");

            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':variant_id', $variant_id);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        }

    }

    static function updateQuantity($cart_id, $variant_id, $quantity)
    {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("UPDATE cart_has_products SET quantity = :quantity WHERE cart_id = :cart_id AND variant_id = :variant_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':variant_id', $variant_id);
        $stmt->bindParam(':quantity', $quantity);

        $stmt->execute();

    }

    static function deleteProductFromCart($conn, $cart_id, $variant_id)
    {
        $stmt = $conn->prepare("DELETE FROM cart_has_products WHERE cart_id = :cart_id AND variant_id = :variant_id");

        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':variant_id', $variant_id);

        $stmt->execute();
    }

    static function getAllCartProductIds($cart_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT p.product_id product_id, p.name pname, p.description description, p.price price, pv.variant_id, pv.name, cart.quantity, i.image FROM cart_has_products cart
                                            LEFT JOIN product_variants pv ON pv.variant_id = cart.variant_id
                                            LEFT JOIN product p ON p.product_id = pv.product_id
                                            LEFT JOIN product_image i ON i.product_image_id = p.image_id
                                            WHERE cart_id = :cart_id");

        $stmt->bindParam(':cart_id', $cart_id);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function deleteAllCartProducts($cart_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM cart_has_products WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id);

        $stmt->execute();
    }

    static function removeProductFromAllCarts($product_id) {
        $variant_ids = ProductVariantsController::getAllProductVariantIds($product_id);

        $array_to_question_marks = implode(',', array_fill(0, count($variant_ids), '?'));

        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM cart_has_products WHERE variant_id IN ($array_to_question_marks)");

        $stmt->execute(array_values($variant_ids));
    }

}