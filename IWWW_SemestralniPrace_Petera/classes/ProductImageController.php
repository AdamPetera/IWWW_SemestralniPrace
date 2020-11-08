<?php


class ProductImageController
{
    static function getProductImage($conn, $product_id, $name) {
        $stmt = $conn->prepare("SELECT mime, image FROM product_image
                    WHERE product_id = :product_id AND name = :name");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $stmt->bindColumn(1, $mime);
        $stmt->bindColumn(2, $image, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);

        return array("mime" => $mime, "image" => $image);
    }

    static function insert($conn, $name, $description, $image, $mime, $product_id) {
        $stmt = $conn->prepare("INSERT INTO product_image (name, desctiption, image, product_id, mime) VALUES (:name, :description, :image, :product_id, :mime)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        $stmt->bindParam(':mime', $mime);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();
    }
}