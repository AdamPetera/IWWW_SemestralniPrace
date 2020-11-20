<?php


class ReviewController
{
    static function getUserProductReview($product_id, $user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM review WHERE user_id = :user_id AND product_id = :product_id");

        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('product_id', $product_id);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return array('rowCount' => $stmt->rowCount(), 'data' => $data);

    }

    static function getAllProductReviews($product_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT r.*, u.firstname, u.lastname FROM review r
                                            JOIN user u ON r.user_id = u.user_id
                                            WHERE product_id = :product_id");

        $stmt->bindParam('product_id', $product_id);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function reviewValidation($name, $body) {
        $validation = array();

        if (empty($name)) {
            $validation["name"] = "Název recenze musí být vyplněn";
        }
        if (empty($body)) {
            $validation["body"] = "Tělo recenze musí být vyplněné";
        }

        return $validation;
    }

    static function insertReview($product_id, $user_id, $name, $rating, $description): int {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO review (product_id, user_id, rating, description, name, dateAdded) VALUES (:product_id, :user_id, :rating, :description, :name, now())");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        return $stmt->rowCount();
    }
}