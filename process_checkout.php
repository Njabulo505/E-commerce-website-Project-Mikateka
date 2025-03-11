<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    header('location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $delivery_method = $_POST['delivery_method'];

    // Get cart items
    $sql = "SELECT * FROM cart WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($cart_items)) {
        // Redirect to cart if no items found
        header('Location: cart.php');
        exit;
    }

    // Calculate total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order into orders table
    $sql = "INSERT INTO orders (username, address, city, province, postal_code, delivery_method, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssd', $user_name, $address, $city, $province, $postal_code, $delivery_method, $total);
    $stmt->execute();

    // Get the order_id of the newly inserted order
    $order_id = $stmt->insert_id;

    $stmt->close();

    // Insert order items into order_items table
    $sql = "INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($cart_items as $item) {
        $stmt->bind_param('iidi', $order_id, $item['product_id'], $item['price'], $item['quantity']);
        $stmt->execute();
    }
    $stmt->close();

    // Clear the cart
    $sql = "DELETE FROM cart WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user_name);
    $stmt->execute();
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Redirect to success page
    header('Location: checkout_success.php');
    exit;
} else {
    // If not a POST request, redirect to the cart page
    header('Location: cart.php');
    exit;
}
?>
