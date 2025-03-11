<?php
session_start();

// Handle logout if the logout button is pressed
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mikateka.com</title>
    <link rel="stylesheet" href="style.css">
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
        <a href="search.php">
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
        <div class="icon-text" id="user-btn">
            <i class="fa-solid fa-user"></i>
         <!--   <span id="user-action"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Login'; ?></span>-->
        </div>
    </div>
</section>

<section id="home"> 
    <a href="shop.php" class="btn">Shop now <i class="fa-solid fa-arrow-right"></i></a>
</section>
<div class="header2">
    Get quality school uniform at a discounted price.
    <div>Up to 60% off</div>
    <div>Shop smart</div>
</div>

<section id="products" class="p-section">
    <h2>Products</h2>
    <div class="content-wrapper">
        <?php
       include('config.php');
       
        $sql = "SELECT id, name, image, description, rating, price FROM products LIMIT 4";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="content">
                        <a href="products.php?id=' . $row["id"] . '">
                            <div class="row">
                                <div class="row-img">
                                    <img src="' . $row["image"] . '" alt="' . $row["name"] . '">
                                </div>
                                <h3>' . $row["name"] . '</h3>
                                <div class="stars">';
                                    for ($i = 0; $i < 4; $i++) {
                                        echo '<a href="#"><i class="fa-solid fa-star"></i></a>';
                                    }
                                    echo '<a href="#">' . $row["rating"] . '/5</a>
                                </div>
                                <div class="row-in">
                                    <div class="row-left">
                                    <a href="products.php?action=add&id=' . $row['id'] . '">
                                            Add to Cart
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>';
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</section>

<a href="shop.php" class="btn">View all products <i class="fa-solid fa-arrow-right"></i></a>

<footer>
    <p>&copy; 2024 Mikateka Primary School. All rights reserved.</p>
</footer>

<!-- User Box -->
<div class="user-box" id="user-box">
    <p>Username: <span><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></span></p>
   <!-- <p>Email: <span><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Not logged in'; ?></span></p>-->

    <?php if (!isset($_SESSION['user_name'])): ?>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    <?php else: ?>
        <form method="post">
            <button type="submit" name="logout" class="logout-btn">Log out</button>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('user-btn');
    const userBox = document.getElementById('user-box');

    function toggleUserBox() {
        userBox.classList.toggle('active');
    }

    userBtn.addEventListener('click', toggleUserBox);
});
</script>

<style>
/* Basic styles for the user box */
.user-box {
    position: absolute;
    top: 60px; /* Adjust based on the height of your header */
    right: 0;
    width: 20rem;
    padding: 1rem;
    text-align: center;
    background: transparent;
    border-radius: 0.5rem;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    transform: scale(0);
    transform-origin: top right;
    transition: transform 0.3s;
    z-index: 1000;
}

.user-box.active {
    transform: scale(1);
}
</style>
</body>
</html>
