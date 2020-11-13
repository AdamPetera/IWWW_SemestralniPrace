<?php


class AttributesController
{
    static function getAllAttributes() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT name FROM `attribute`");

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
}