<?php


class ProductVariantsController
{
    static function getAllProductVariants($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM product_variants WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        $temp_variants = $stmt->fetchAll();
        $variants = array();

        foreach ($temp_variants as $variant) {
            array_push($variants, $variant['name']);
        }
        return $variants;
    }

    static function getVariantIdByNameAndProductId($name, $product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM product_variants WHERE product_id = :product_id AND name = :name");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $variant = $stmt->fetchAll();

        return (int) $variant[0]['variant_id'];

    }

    static function insertVariantOfProduct($product_id, $name) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO product_variants (name, product_id) VALUES (:name, :product_id)");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $stmt->rowCount();
    }

    static function removeVariantOfProduct($product_id, $name) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM product_variants WHERE product_id = :product_id AND name = :name");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);

        $stmt->execute();
    }
}