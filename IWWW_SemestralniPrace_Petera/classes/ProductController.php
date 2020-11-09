<?php


class ProductController
{
    static function getAllSticks(): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT p.* FROM product p
                    JOIN product_has_category pc ON p.product_id = pc.product_id
                    JOIN category c ON c.category_id = pc.category_id
                    WHERE c.identifier = 'stick'");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function getAllSticksByPriceRange($lower, $higher): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT p.* FROM product p
                    JOIN product_has_category pc ON p.product_id = pc.product_id
                    JOIN category c ON c.category_id = pc.category_id
                    WHERE c.identifier = 'stick'
                    AND p.price >= $lower
                    AND p.price <= $higher");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function getAllSpecifiedItems($identifier): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT p.* FROM product p
                    JOIN product_has_category pc ON p.product_id = pc.product_id
                    JOIN category c ON c.category_id = pc.category_id
                    WHERE c.identifier = '$identifier'");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function getAllSpecifiedItemName($identifier): string {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT name FROM category
                                            WHERE identifier = '$identifier'");
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }
    static function getProductById($conn, $product_id) {
        $stmt = $conn->prepare("SELECT * FROM product where product_id = ?");
        $stmt->execute([$product_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static function insertProductAndImageValidation($name, $description, $price, $img_name) {
        $validation = array();
        if (empty($name)) {
            $validation["name"] = "Název produktu musí být vyplněn";
        }
        if (empty($description)) {
            $validation["description"] = "Popis produktu musí být vyplněn";
        }
        if (empty($price)) {
            $validation["price"] = "Cena produktu musí být vyplněna";
        }
        if (empty($img_name)) {
            $validation["img_name"] = "Název obrázku musí být vyplněn";
        }

        return $validation;
    }

    static function insertProduct($name, $description, $price) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO product (name, description, price) VALUES (:name, :description, :price)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);

        $stmt->execute();
    }


}