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

    static function getCategoryIdByName($name) {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT category_id FROM category WHERE name = :name");
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    static function getAllCategoriesNamesAndIdentifiers() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT identifier, name FROM category");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function addCategory($name, $identifier) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO category (identifier, name) VALUES (:identifier, :name)");
        $stmt->bindParam(':identifier', $identifier);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        return $stmt->rowCount();

    }

    static function deleteCategory($identifier) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM category WHERE identifier = :identifier");
        $stmt->bindParam(':identifier', $identifier);

        $stmt->execute();

        return $stmt->rowCount();
    }
}