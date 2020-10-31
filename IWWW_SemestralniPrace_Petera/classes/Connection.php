<?php

define("DB_HOST", "localhost");
define("DB_NAME", "web");
define("DB_USER", "root");
define("DB_PASSWORD", "");

class Connection {

    static private $instance = NULL;

    static function getPdoInstance(): PDO {
        try {
            if (self::$instance == NULL) {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", DB_USER, DB_PASSWORD);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "Connected successfully";
                self::$instance = $conn;
            }
            return self::$instance;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

}