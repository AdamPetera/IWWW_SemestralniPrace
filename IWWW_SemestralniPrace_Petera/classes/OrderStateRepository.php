<?php


class OrderStateRepository
{
    static function getAllOrderStates() {
        $conn = Connection::getPdoInstance();

        $stmt = $conn->prepare("SELECT human_readable FROM order_state");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}