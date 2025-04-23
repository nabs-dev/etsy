<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->execute([$userId]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Products</title>
    <style>
        /* Internal CSS */
        body { font-family: Arial, sans-serif; }
        .product-grid { display: flex; flex-wrap: wrap; gap: 20px; }
        .product { border: 1px solid #ddd; padding: 10px; width: calc(33% - 20px); text-align: center; }
        .product img { max-width: 100%; }
    </style>
</head>
<body>

    <h1>Your Listed Products</h1>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <p>$<?php echo $product['price']; ?></p>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> | 
                <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
