<?php


class ProductHasCategoryController
{
    static function getCategoryId($category) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT category_id FROM category WHERE name = :category");

        $stmt->bindParam(':category', $category);

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    static function insert($product_id, $category) {
        $conn = Connection::getPdoInstance();
        $category_id = self::getCategoryId($category);

        $stmt = $conn->prepare("INSERT INTO product_has_category (category_id, product_id) VALUES (:category_id, :product_id)");

        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

    }

    static function removeCategoryOfProduct($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM product_has_category WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }

    static function updateProductCategory($product_id, $category) {
        $conn = Connection::getPdoInstance();
        $category_id = self::getCategoryId($category);
        $stmt = $conn->prepare("UPDATE product_has_category SET category_id = :category_id WHERE product_id = :product_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        return $stmt->rowCount();

    }
}