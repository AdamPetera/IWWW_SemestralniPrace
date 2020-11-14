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

    static function updateProduct($product_id, $name, $description, $price) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE product SET name = :name, description = :description, price = :price WHERE product_id = :product_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        return $stmt->rowCount();

    }

    static function getProductPrice($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT price FROM product
                                            WHERE product_id = '$product_id'");

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    static function getAllProductsByIds($product_ids_and_keys) {
        $conn = Connection::getPdoInstance();
        $array_to_question_marks = implode(',', array_fill(0, count($product_ids_and_keys), '?'));
        $stmt = $conn->prepare('SELECT * FROM product WHERE product_id IN (' . $array_to_question_marks . ')');
        (int) $i = 1;
        foreach ($product_ids_and_keys as $k => $id)
            $stmt->bindValue(($i++), $k);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function getAllProductAttributes($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT a.name, a.human_readable, pa.value FROM product p
                    LEFT JOIN product_has_attributes pa ON p.product_id = pa.product_id
                    LEFT JOIN `attribute` a ON a.attribute_id = pa.attribute_id
                    WHERE p.product_id = :product_id");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function getProductCategory($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT c.identifier FROM product p
                    LEFT JOIN product_has_category pc ON p.product_id = pc.product_id
                    LEFT JOIN category c ON c.category_id = pc.category_id
                    WHERE p.product_id = :product_id");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    static function setVariables($post, $row): array {
        if (isset($post["name"])) {
            if (!empty($post["name"])) {
                $name = $post["name"];
            } else {
                $name = $row["name"];
            }
        }
        if (isset($post["description"])) {
            if (!empty($post["description"])) {
                $description = $post["description"];
            } else {
                $description = $row["description"];
            }
        }
        if (isset($post["price"])) {
            if (!empty($post["price"])) {
                $price = $post["price"];
            } else {
                $price = $row["price"];
            }
        }

        return array('name' => $name, 'description' => $description, 'price' => $price);
    }

    static function deleteProduct($product_id) {
        CartHasProductsController::removeProductFromAllCarts($product_id);
        ProductHasAttributesController::removeAllProductAttributes($product_id);
        OrderHasProductsController::removeAllProductFromOrder($product_id);
        ProductHasCategoryController::removeCategoryOfProduct($product_id);
        ProductImageController::removeAllImagesOfProduct($product_id);
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM product WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
}