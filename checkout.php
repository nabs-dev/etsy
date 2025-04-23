<?php
session_start();
require 'db.php';

// Sample user check — make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed to checkout.'); window.location.href = 'login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// If the user clicked "Place Order"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear cart (for simplicity, we're not saving order history in this basic version)
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    echo "<script>alert('Order placed successfully!'); window.location.href = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Etsy Clone</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
        }
        .checkout-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 5px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-details h4 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .item-details p {
            margin: 0;
            color: #666;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 30px;
        }
        .checkout-btn {
            display: block;
            margin: 30px auto 0;
            padding: 12px 30px;
            background: #ff5a5f;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .checkout-btn:hover {
            background: #e24d50;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>

        <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Price: $<?php echo $item['price']; ?> × <?php echo $item['quantity']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="total">Total: $<?php echo number_format($total, 2); ?></div>

            <form method="post">
                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
