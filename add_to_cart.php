<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('location: login.php');
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['size'])) {
    error_log("Product ID or size not specified");
    echo json_encode(["success" => false, "message" => "Product ID or size not specified"]);
    exit;
}

$product_id = $_GET['id'];
$size = $_GET['size'];
error_log("Product ID received: " . $product_id);
error_log("Size received: " . $size);

include 'config.php';

// Get the logged-in user's username
$username = $_SESSION['user_name'];

// Fetch product details from the products table
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE username = ? AND product_id = ? AND size = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $username, $product_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();

    if ($cart_item) {
        // Product exists in the cart, update the quantity
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $cart_item['id']);
        $stmt->execute();
    } else {
        // Product does not exist in the cart, insert a new row
        $sql = "INSERT INTO cart (username, product_id, name, image, price, quantity, size) VALUES (?, ?, ?, ?, ?, 1, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissds', $username, $product_id, $product['name'], $product['image'], $product['price'], $size);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
    header('location: cart.php');
    echo json_encode(["success" => true, "message" => "Product added to cart"]);
    exit;
} else {
    echo json_encode(["success" => false, "message" => "Product not found"]);
    exit;
}
?>
