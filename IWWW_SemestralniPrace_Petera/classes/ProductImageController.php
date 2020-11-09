<?php


class ProductImageController
{

    static function getProductImage($product_id, $name) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT image FROM product_image
                    WHERE product_id = :product_id AND name = :name");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    static function insert($name, $image, $product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO product_image (name, image, product_id) VALUES (:name, :image, :product_id)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();
    }
}