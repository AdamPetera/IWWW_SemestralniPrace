<?php


class ProductHasAttributesController
{
    static function insertPHA($product_id, $attribute_id, $value) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO product_has_attributes (product_id, attribute_id, value)
                                    VALUES (:product_id, :attribute_id, :value)");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':attribute_id', $attribute_id);
        $stmt->bindParam(':value', $value);

        $stmt->execute();
    }

    static function getAllProductAttributes($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT a.attribute_id, a.human_readable, pha.value FROM product_has_attributes pha
                                            LEFT JOIN `attribute` a ON a.attribute_id = pha.attribute_id
                                            WHERE product_id = :product_id");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function deleteAttribute($product_id, $attribute_id, $value) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM product_has_attributes WHERE product_id = :product_id AND attribute_id = :attribute_id AND value = :value");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':attribute_id', $attribute_id);
        $stmt->bindParam(':value', $value);

        $stmt->execute();
    }
}