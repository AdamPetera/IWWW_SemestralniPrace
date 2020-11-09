<?php


class ProductHasCategoryController
{
    static function insert($product_id, $category) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT category_id FROM category WHERE name = :category");

        $stmt->bindParam(':category', $category);

        $stmt->execute();

        $category_id = (int) $stmt->fetchColumn();

        $stmt = $conn->prepare("INSERT INTO product_has_category (category_id, product_id) VALUES (:category_id, :product_id)");

        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

    }
}