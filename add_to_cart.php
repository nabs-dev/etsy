<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['product_id'])) {
        echo "<script>alert('Product ID missing!'); window.location.href='index.php';</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Check if the product already exists in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        // Insert new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    echo "<script>alert('Product added to cart!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
}
?>
