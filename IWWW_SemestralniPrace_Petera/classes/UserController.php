<?php


class UserController
{
    static function getUserById($user_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static function getAllUsers(): array {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user");
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

    static function getAllUsersWithRoles() {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT u.*, r.name rolename FROM user u
                                            LEFT JOIN user_has_role uhr ON u.user_id = uhr.user_id
                                            LEFT JOIN role r ON r.role_id = uhr.role_id
                                            ORDER BY rolename");
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

    static function emailExistsReturnArray($conn, $email): array {
        $chk = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $chk->bindParam(':email', $email);

        $chk->execute();
        $row = $chk->fetch(PDO::FETCH_ASSOC);

        return array("rowCount" => $chk->rowCount(),
                        "row" => $row);
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

    static function updateUser($conn, $currEmail, $firstname, $lastname, $email, $phone, $password) {
        $stmt = $conn->prepare("UPDATE user SET firstname = :firstname, lastname = :lastname,
                               email = :email, phone = :phone, password = :password WHERE email = :currEmail");

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':currEmail', $currEmail);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        return $stmt->rowCount();
    }

    static function loginUser($email) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM user u
                                JOIN user_has_role ur ON u.user_id = ur.user_id
                                LEFT JOIN role r ON r.role_id = ur.role_id
                                WHERE u.email = :email");

        $stmt->bindParam(':email', $email);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    static function getUserIdByEmail($email) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return ((int)$stmt->fetchColumn());
    }

    static function setVariables($post, $row): array {
        $conn = Connection::getPdoInstance();
        $emailRowCount = 0;
        if (isset($post["firstname"])) {
            if (!empty($post["firstname"])) {
                $firstname = $post["firstname"];
            } else {
                $firstname = $row["firstname"];
            }
        }
        if (isset($post["lastname"])) {
            if (!empty($post["lastname"])) {
                $lastname = $post["lastname"];
            } else {
                $lastname = $row["lastname"];
            }
        }
        if (isset($post["email"])) {
            if (!empty($post["email"])) {
                $email = $post["email"];
                $emailRowCount = UserController::emailExists($conn, $post["email"]);
            } else {
                $email = $row["email"];
            }
        }
        if (isset($post["phone"])) {
            if (!empty($post["phone"])) {
                $phone = $post["phone"];
            } else {
                $phone = $row["phone"];
            }
        }
        if (isset($post["password"])) {
            if (!empty($post["password"])) {
                $password = password_hash($post["password"], PASSWORD_DEFAULT);
            } else {
                $password = $row["password"];
            }
        }

        return array('emailRowCount' => $emailRowCount, 'firstname' => $firstname,
            'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'password' => $password);
    }
}