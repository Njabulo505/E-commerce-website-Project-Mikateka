<?php
session_start();
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<section id="header">
    <h1 class="logo">Mikateka.com</h1>
    <ul id="nbar">
        <li><a href="index.php">HOME</a></li>
        <li><a href="shop.php">SHOP</a></li>
        <li><a href="about.html">ABOUT US</a></li>
        <li><a href="contact.html">CONTACT</a></li>
    </ul>
</section>
<body>
    <h2>Search</h2>
    <form action="search.php" method="GET">
        <input type="text" name="query" placeholder="Enter your search query">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</body>
<?php
if(isset($_GET['query'])) {
    // Get the search query
    $search_query = $_GET['query'];

    // Create connection to database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to search for products by name
    $sql = "SELECT id, name, image, description, rating, price FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);

    // Bind parameter
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_param);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Display search results
    echo '<div class="search-results-container">';
    if ($result->num_rows > 0) {
        echo "<h2>Search Results</h2>";
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card">
                    <a href="products.php?id=' . $row["id"] . '">
                        <div class="product-image">
                            <img src="' . $row["image"] . '" alt="' . $row["name"] . '">
                        </div>
                        <div class="product-details">
                            <h3>' . $row["name"] . '</h3>
                            <div class="product-rating">';
                                for ($i = 0; $i < 4; $i++) {
                                    echo '<i class="fa-solid fa-star"></i>';
                                }
                                echo '<span>' . $row["rating"] . '/5</span>
                            </div>
                            <div class="product-price">
                                R' . $row["price"] . '
                            </div>
                            <div class="add-to-cart">
                                <a href="Add_to_cart.php?action=add&id=' . $row['id'] . '">
                                    Add to Cart
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>';
        }
    } else {
        echo "<p>No results found.</p>";
    }
    echo '</div>';

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
