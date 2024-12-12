<?php
    const DB_SERVER = "LOCALHOST";
    const DB_USER = "user";
    const DB_PASS = "";
    const DB_NAME = "name";

    function getDB(){
        try {
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch (PDOException $e) { 
            die("Error: Could not connect.". $e->getMessage());
        }
    }   
?>