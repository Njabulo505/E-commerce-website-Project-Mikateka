<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    header('location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'];

// Fetch cart items to display the total
$sql = "SELECT * FROM cart WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
<header>
    <h1>Mikateka.com</h1>
</header>

<main>
    <div class="checkout-container">
        <h2>Order Summary</h2>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span>Price: R<?php echo htmlspecialchars($item['price']); ?></span>
                    <span>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="total">
            <p>Total: R<?php echo number_format($total, 2); ?></p>
        </div>

        <form action="process_checkout.php" method="post">
            <h2>Shipping Information</h2>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
            
            <label for="province">Province:</label>
            <input type="text" id="province" name="province" required>
            
            <label for="postal_code">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" required>
            
            <h2>Delivery Method</h2>
            <label for="delivery_method">Choose a delivery method:</label>
            <select id="delivery_method" name="delivery_method" required>
                <option value="standard">Standard Delivery</option>
                <option value="express">Express Delivery</option>
            </select>
            
            <h2>Payment Information</h2>
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>
            
            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" required>
            
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            
            <button type="submit">Place Order</button>
        </form>
    </div>
</main>
</body>
</html>
