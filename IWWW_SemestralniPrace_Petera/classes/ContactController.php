<?php


class ContactController
{
    static function contactValidation($name, $email, $question_name, $question) {
        $validation = array();
        if (empty($name)) {
            $validation["name"] = "Jméno musí být vyplněné";
        }
        if (empty($email)) {
            $validation["email"] = "Email musí být vyplněn";
        }
        if (empty($question_name)) {
            $validation["question_name"] = "Název otázky musí být vyplněn";
        }
        if (empty($question)) {
            $validation["question"] = "Dotaz musí být vyplněn";
        }

        return $validation;
    }

    static function insertMessage($name, $email, $question, $message, $phone = null) {
        $conn = Connection::getPdoInstance();
        $stmt = $conn->prepare('INSERT INTO contact (name, email, phone, question, message) VALUES (:name, :email, :phone, :question, :message)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':phone', $phone);

        $stmt->execute();

    }
}