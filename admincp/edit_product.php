<?php
session_start();


include('config/config.php');

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];
    $productName = $_POST['productName'];
    $categoryID = $_POST['categoryID'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $imageURL = $_POST['imageURL'];

    $stmt = $mysqli->prepare("UPDATE Products SET ProductName = ?, CategoryID = ?, Price = ?, Stock = ?, Description = ?, ImageURL = ? WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("sidissi", $productName, $categoryID, $price, $stock, $description, $imageURL, $productID);

    if ($stmt->execute()) {
        $success = "Sản phẩm đã được cập nhật thành công.";
        header("Location: view_categories.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $productID = $_GET['productID'];
    $stmt = $mysqli->prepare("SELECT ProductName, CategoryID, Price, Stock, Description, ImageURL FROM Products WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($productName, $categoryID, $price, $stock, $description, $imageURL);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" type="text/css" href="../login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Sửa sản phẩm</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="edit_product.php" method="post">
            <input type="hidden" name="productID" value="<?php echo htmlspecialchars($productID); ?>">
            <label for="productName">Tên sản phẩm:</label>
            <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($productName); ?>" required><br><br>
            <label for="categoryID">ID danh mục:</label>
            <input type="number" id="categoryID" name="categoryID" value="<?php echo htmlspecialchars($categoryID); ?>" required><br><br>
            <label for="price">Giá:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required><br><br>
            <label for="stock">Số lượng:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock); ?>" required><br><br>
            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea><br><br>
            <label for="imageURL">URL hình ảnh:</label>
            <input type="text" id="imageURL" name="imageURL" value="<?php echo htmlspecialchars($imageURL); ?>"><br><br>
            <input type="submit" value="Cập nhật">
        </form>
        <br>
        <a href="view_categories.php" class="button">Xem sản phẩm</a>
    </div>
</body>
</html>