<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mystery Vinyl Record Shop</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Mystery Vinyl Record Shop</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </header>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="button">Logout</a>
        <?php else: ?>
            <a href="login.php" class="button">Login</a>
            <a href="register.php" class="button">Register</a>
        <?php endif; ?>
        <form action="order.php" method="post">
            <label for="genre">Select Genre:</label>
            <select id="genre" name="genre" required>
                <option value="Rock">Rock</option>
                <option value="Jazz">Jazz</option>
                <option value="Pop">Pop</option>
                <option value="Classical">Classical</option>
                <option value="Hip-hop">Hip-hop</option>
            </select>
            <input type="submit" value="Order Mystery Vinyl">
        </form>
        <div class="records">
            <?php include 'fetch_orders.php'; ?>
        </div>
        <section class="about">
            <p>Discover hidden musical gems from your favorite genres. To read more about us click <a href="about.php">here</a></p>
        </section>
    </div>
</body>
</html>
