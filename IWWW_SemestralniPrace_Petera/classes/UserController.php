<?php


class UserController
{
    static function emailExists($conn, $email): int {
        $chk = $conn->prepare("SELECT email FROM user WHERE email = :email");
        $chk->bindParam(':email', $email);

        $chk->execute();

        return $chk->rowCount();
    }

    static function insertUser($conn, $firstname, $lastname, $email, $phone, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, phone, password)
                                                        VALUES (:firstname, :lastname, :email, :phone, :password)");

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt;
    }

    static function registerUserValidation($firstname, $lastname, $email, $phone, $password) {
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
        if (empty($password)) {
            $validation["password"] = "Heslo musí být vyplněné";
        }

        return $validation;
    }
}