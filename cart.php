<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

// Fetch product details
$products = [];
foreach ($cartItems as $item) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$item['product_id']]);
    $product = $stmt->fetch();
    $products[] = ['product' => $product, 'quantity' => $item['quantity']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Internal CSS */
        body { font-family: Arial, sans-serif; }
        .cart-item { display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .cart-item img { width: 100px; }
        .cart-item-details { flex-grow: 1; margin-left: 10px; }
    </style>
</head>
<body>

    <h1>Your Shopping Cart</h1>
    <div class="cart">
        <?php foreach ($products as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['product']['image']; ?>" alt="<?php echo $item['product']['name']; ?>">
                <div class="cart-item-details">
                    <h3><?php echo $item['product']['name']; ?></h3>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                    <p>$<?php echo $item['product']['price']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button onclick="window.location.href='checkout.php'">Proceed to Checkout</button>

</body>
</html>
