<?php


class Stick
{
    static function getAllSticks(): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM stick");
        $stmt->execute();

        return $stmt->fetchAll();
    }

}