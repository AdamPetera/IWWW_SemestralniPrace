<?php


class CartController
{
    static function insertIntoCart($conn, $user_id) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    static function getCartId($conn, $user_id) {
        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = :user_id");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}