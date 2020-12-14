<?php


class CategoryController
{
    static function getAllCategories() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT name FROM category");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function getProductCategory($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT c.identifier FROM product p 
                                    LEFT JOIN product_has_category pc ON p.product_id = pc.product_id
                                    LEFT JOIN category c ON c.category_id = pc.category_id
                                    WHERE p.product_id = :product_id");
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}