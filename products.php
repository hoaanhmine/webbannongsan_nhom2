<?php
include('admincp/config/config.php');

$categoryID = isset($_GET['categoryID']) ? $_GET['categoryID'] : 0;

$stmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE CategoryID = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

$stmt->bind_param("i", $categoryID);
$stmt->execute();
$stmt->bind_result($productID, $productName, $price, $stock, $description, $imageURL);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Products</h2>
        <?php while ($stmt->fetch()): ?>
            <div class="product">
                <h3><?php echo htmlspecialchars($productName); ?></h3>
                <p>Price: $<?php echo htmlspecialchars($price); ?></p>
                <p>Stock: <?php echo htmlspecialchars($stock); ?></p>
                <p>Description: <?php echo htmlspecialchars($description); ?></p>
                <?php if ($imageURL): ?>
                    <img src="<?php echo htmlspecialchars($imageURL); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
?>