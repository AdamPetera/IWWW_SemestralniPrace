<?php


class AddressController
{
    static function getUsersAddress($user_id): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM address WHERE user_id = :user_id");

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return array('rowCount' => $stmt->rowCount(), 'row' => $row);
    }

    static function addressValidation($street, $no, $city, $zipcode) {
        $validation = array();
        if (empty($street)) {
            $validation["street"] = "Ulice musí být vyplněná";
        }
        if (empty($no)) {
            $validation["no"] = "Číslo popisné musí být vyplněné";
        }
        if (empty($city)) {
            $validation["city"] = "Obec musí být vyplněná";
        }
        if (empty($zipcode)) {
            $validation["zipcode"] = "PSČ musí být vyplněné";
        }

        return $validation;
    }

    static function insertAddress($street, $no, $city, $zipcode, $user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("INSERT INTO address (street, no, city, zipcode, user_id) VALUES (:street, :no, :city, :zipcode, :user_id)");

        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':no', $no);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return array("rowCount" => $stmt->rowCount(),
            "address_id" => $conn->lastInsertId());

    }

    static function getAddressByUserId($user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM address WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static function updateAddress($street, $no, $city, $zipcode, $user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE address SET street = :street, no = :no,
                                city = :city, zipcode = :zipcode WHERE user_id = :user_id");
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':no', $no);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt->rowCount();
    }

    static function setVariables($post, $row): array {
        if (isset($post["street"])) {
            if (!empty($post["street"])) {
                $street = $post["street"];
            } else {
                $street = $row["street"];
            }
        }
        if (isset($post["no"])) {
            if (!empty($post["no"])) {
                $no = $post["no"];
            } else {
                $no = $row["no"];
            }
        }
        if (isset($post["city"])) {
            if (!empty($post["city"])) {
                $city = $post["city"];
            } else {
                $city = $row["city"];
            }
        }
        if (isset($post["zipcode"])) {
            if (!empty($post["zipcode"])) {
                $zipcode = $post["zipcode"];
            } else {
                $zipcode = $row["zipcode"];
            }
        }

        return array('street' => $street,
            'no' => $no, 'city' => $city, 'zipcode' => $zipcode);
    }

    static function checkIfAddressExists($user_id, $street, $no, $city, $zipcode) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM address WHERE no = :no AND zipcode = :zipcode LIMIT 1");
        $stmt->bindParam(':no', $no);
        $stmt->bindParam(':zipcode', $zipcode);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $row = self::insertAddress($street, $no, $city, $zipcode, $user_id);
            return $row['address_id'];
        }

        $row = $stmt->fetch();

        return (int) $row['address_id'];
    }
}