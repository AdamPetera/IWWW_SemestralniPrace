<?php


class UserController
{
    static function getAllUsers(): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user");
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

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

    static function loginUser($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM user u
                                JOIN user_has_role ur ON u.user_id = ur.user_id
                                LEFT JOIN role r ON r.role_id = ur.role_id
                                WHERE u.email = :email");

        $stmt->bindParam(':email', $email);

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

    static function loginUserValidation($email, $password) {
        $validation = array();

        if (empty($email)) {
            $validation["email"] = "Email musí být vyplněn";
        }
        if (empty($password)) {
            $validation["password"] = "Heslo musí být vyplněné";
        }

        return $validation;
    }
}