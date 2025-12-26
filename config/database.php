<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_managment');
define('DB_USER', 'root');
define('DB_PASS', 'root');

function getConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

?>