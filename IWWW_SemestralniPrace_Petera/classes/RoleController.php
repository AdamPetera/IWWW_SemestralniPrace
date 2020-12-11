<?php


class RoleController
{
    static function getAllRoles() {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT * FROM `role`");

        $stmt->execute();

        $temp_roles = $stmt->fetchAll();
        $roles = array();

        foreach ($temp_roles as $role) {
            array_push($roles, $role['name']);
        }

        return $roles;
    }

    static function getRoleIdByName($name) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("SELECT role_id FROM `role` WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return ((int)$stmt->fetchColumn());
    }

    static function updateUserRole($user_id, $role_id) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare("UPDATE user_has_role SET role_id = :role_id WHERE user_id = :user_id");
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt->rowCount();
    }
}