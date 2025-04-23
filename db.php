<?php
$host = 'localhost';
$db = 'dbbkufcezie0qg';
$user = 'u8gr0sjr9p4p4';
$pass = '9yxuqyo3mt85';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
