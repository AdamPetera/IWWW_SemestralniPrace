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

}