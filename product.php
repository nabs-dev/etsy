<?php
session_start();
require 'db.php';

$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <style>
        /* Internal CSS */
        body { font-family: Arial, sans-serif; }
        .product-detail { text-align: center; }
        .product-detail img { max-width: 100%; }
    </style>
</head>
<body>

    <div class="product-detail">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h1><?php echo $product['name']; ?></h1>
        <p><?php echo $product['description']; ?></p>
        <p>$<?php echo $product['price']; ?></p>
        <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
    </div>

    <script>
        function addToCart(productId) {
            alert("Product " + productId + " added to cart.");
            // You can implement AJAX here to add the product to the cart in the database
        }
    </script>

</body>
</html>
