<?php


class OrderStateRepository
{
    static function getAllOrderStates() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT human_readable FROM order_state");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static function getStateIdByHumanReadable($value) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT order_state_id FROM order_state WHERE human_readable = :value");
        $stmt->bindParam(':value', $value);

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}