<?php


class UserHasRoleController
{
    static function insertIntoUHRNormalUser($conn, $userID) {
        $stmt = $conn->prepare("INSERT INTO user_has_role(user_id, role_id)
                                            VALUES ($userID, 2)");
        $stmt->execute();

    }
}