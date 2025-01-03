<?php


include('config/config.php');

$error = '';
$success = '';

// Handle category addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_category') {
    $categoryName = $_POST['categoryName'];

    $stmt = $mysqli->prepare("INSERT INTO Categories (CategoryName) VALUES (?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("s", $categoryName);

    if ($stmt->execute()) {
        $success = "Danh mục đã được thêm thành công.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle category deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_category') {
    $categoryID = $_POST['categoryID'];

    $stmt = $mysqli->prepare("DELETE FROM Categories WHERE CategoryID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("i", $categoryID);

    if ($stmt->execute()) {
        $success = "Danh mục đã được xóa thành công.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_product') {
    $productID = $_POST['productID'];

    $stmt = $mysqli->prepare("DELETE FROM Products WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("i", $productID);

    if ($stmt->execute()) {
        $success = "Sản phẩm đã được xóa thành công.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all categories
$categoryStmt = $mysqli->prepare("SELECT CategoryID, CategoryName FROM Categories");
if ($categoryStmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}
$categoryStmt->execute();
$categoryStmt->bind_result($categoryID, $categoryName);
$categories = [];
while ($categoryStmt->fetch()) {
    $categories[] = ['CategoryID' => $categoryID, 'CategoryName' => $categoryName];
}
$categoryStmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh mục sản phẩm</title>
    <link rel="stylesheet" type="text/css" href="../login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Danh mục sản phẩm</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="view_categories.php" method="post">
            <input type="hidden" name="action" value="add_category">
            <label for="categoryName">Tên danh mục:</label>
            <input type="text" id="categoryName" name="categoryName" required>
            <input type="submit" value="Thêm danh mục">
        </form>
        <?php foreach ($categories as $category): ?>
            <div class="category">
                <h3><?php echo htmlspecialchars($category['CategoryName']); ?> (ID: <?php echo htmlspecialchars($category['CategoryID']); ?>)</h3>
                <form action="view_categories.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="categoryID" value="<?php echo htmlspecialchars($category['CategoryID']); ?>">
                    <input type="submit" value="Xóa">
                </form>
                <a href="edit_category.php?categoryID=<?php echo htmlspecialchars($category['CategoryID']); ?>">Sửa</a>
                <?php
                // Fetch products for this category
                $productStmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE CategoryID = ?");
                if ($productStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                }
                $productStmt->bind_param("i", $category['CategoryID']);
                $productStmt->execute();
                $productStmt->bind_result($productID, $productName, $price, $stock, $description, $imageURL);
                ?>
                <ul>
                    <?php while ($productStmt->fetch()): ?>
                        <li>
                            <h4><?php echo htmlspecialchars($productName); ?> (ID: <?php echo htmlspecialchars($productID); ?>)</h4>
                            <p>Giá: <?php echo htmlspecialchars($price); ?></p>
                            <p>Số lượng: <?php echo htmlspecialchars($stock); ?></p>
                            <p>Mô tả: <?php echo htmlspecialchars($description); ?></p>
                            <?php if ($imageURL): ?>
                                <img src="<?php echo htmlspecialchars($imageURL); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
                            <?php endif; ?>
                            <form action="view_categories.php" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($productID); ?>">
                                <input type="submit" value="Xóa">
                            </form>
                            <a href="edit_product.php?productID=<?php echo htmlspecialchars($productID); ?>">Sửa</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php $productStmt->close(); ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>