<?php


class CategoryController
{
    static function getAllCategories() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT name FROM category");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}