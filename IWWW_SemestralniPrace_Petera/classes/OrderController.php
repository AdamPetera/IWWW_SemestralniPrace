<?php


class OrderController
{
    static function userInfoAndAddressValidation($firstname, $lastname, $email, $phone, $street, $no, $city, $zipcode) {

        $validation = array();

        if (empty($firstname)) {
            $validation["firstname"] = "Jméno musí být vyplněné";
        }
        if (empty($lastname)) {
            $validation["lastname"] = "Příjmení musí být vyplněné";
        }
        if (empty($email)) {
            $validation["email"] = "Email musí být vyplněn";
        }
        if (empty($phone)) {
            $validation["phone"] = "Telefonní číslo musí být vyplněné";
        }
        if (empty($street)) {
            $validation["street"] = "Ulice musí byt vyplněna!";
        }
        if (empty($no)) {
            $validation["no"] = "Číslo popisné musí byt vyplněno!";
        }
        if (empty($city)) {
            $validation["city"] = "Obec musí byt vyplněna!";
        }
        if (empty($zipcode)) {
            $validation["zipcode"] = "PSČ musí byt vyplněno!";
        }

        return $validation;
    }

    static function insertOrder($conn, $user_id, $price, $order_number) {
        $stmt = $conn->prepare("INSERT INTO `order` (user_id, price, order_number, order_date) VALUES (:user_id, :price, :order_number, :order_date)");

        $order_date = date("d. m. Y");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':order_date', $order_date);

        $stmt->execute();
    }

    static function getAllUsersOrders($user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM `order` WHERE user_id = :user_id");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function getOrderByOrderNumber($order_number) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT o.*, os.human_readable FROM `order` o 
                                            LEFT JOIN order_state os ON o.order_state_id = os.order_state_id
                                            WHERE order_number = :order_number");

        $stmt->bindParam(':order_number', $order_number);
        $stmt->execute();

        return $stmt->fetch();

    }

    static function verificationOfUsersOrder($order_number, $user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT user_id FROM `order` WHERE order_number = :order_number");
        $stmt->bindParam(':order_number', $order_number);
        $stmt->execute();

        $id = (int) $stmt->fetchColumn();

        return $user_id == $id;
    }
}