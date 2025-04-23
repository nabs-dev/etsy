<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $seller_id = $_SESSION['user_id'];

    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $newFilename = time() . "_" . $filename;
        $targetFile = $targetDir . $newFilename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, seller_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $imagePath, $seller_id]);

    echo "<script>alert('Product added successfully'); window.location.href='index.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
        }

        .form-container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            background: #ff5a5f;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #e14a4f;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #555;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" step="0.01" name="price" placeholder="Product Price" required>
            <textarea name="description" rows="4" placeholder="Product Description" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>
        <a class="back-link" href="index.php">← Back to Home</a>
    </div>
</body>
</html>
