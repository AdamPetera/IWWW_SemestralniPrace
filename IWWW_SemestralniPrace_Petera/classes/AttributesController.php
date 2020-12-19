<?php


class AttributesController
{
    static function getAllAttributes() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT name, human_readable FROM `attribute`");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function getAttributeId($name) {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT attribute_id FROM `attribute` WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return ((int)$stmt->fetchColumn());
    }

    static function addAttribute($name, $human_readable) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO `attribute` (name, human_readable) VALUES (:name, :human_readable)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':human_readable', $human_readable);

        $stmt->execute();

        return $stmt->rowCount();
    }

    static function deleteAttribute($name) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("DELETE FROM `attribute` WHERE name = :name");
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        return $stmt->rowCount();
    }
}