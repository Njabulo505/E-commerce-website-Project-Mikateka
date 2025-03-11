<?php
session_start();
include('config.php');

if (!isset($_GET['id'])) {
    echo "Product ID not specified";
    exit;
}

$product_id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>
<body>
<section id="header">
    <h1 class="logo">Mikateka.com</h1>
    <ul id="nbar">
        <li><a class="active" href="index.php">HOME</a></li>
        <li><a href="shop.php">SHOP</a></li>
        <li><a href="about.html">ABOUT US</a></li>
        <li><a href="contact.html">CONTACT</a></li>
    </ul>
    <div class="right">
        <a href="search.html">
            <div class="icon-text">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Search</span>
            </div>
        </a>
        <a href="cart.php">
            <div class="icon-text">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Cart</span>
            </div>
        </a>
        <a href="login.php">
            <div class="icon-text">
                <i class="fa-solid fa-user"></i>
                <span>Login</span>
            </div>
        </a>
        <a style="border-style:solid" href="Register.php">Sign up</a>
    </div>
</section>

<section id="product-details">
    <div class="product-img">
        <img id="product-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="product-info">
        <h2 id="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p id="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
        <div class="stars">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <a href="#"><i class="fa-solid fa-star"></i></a>
            <?php endfor; ?>
            <a href="#"><?php echo htmlspecialchars($product['rating']); ?>/5</a>
        </div>
        <p>Price: R<span id="product-price"><?php echo htmlspecialchars($product['price']); ?></span></p>
        
        <form id="add-to-cart-form" action="Add_to_cart.php" method="GET">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>">
            <label for="size">Size:</label>
            <select name="size" id="size" required>
                <option value="">Select Size</option>
                <option value="S">Small</option>
                <option value="M">Medium</option>
                <option value="L">Large</option>
                <option value="XL">Extra Large</option>
            </select>
            <button type="submit">Add to Cart</button>
        </form>
    </div>
</section>

<script src="cart.js"></script>
</body>
</html>
