<?php
include('config/config.php');

$error = '';
$success = '';

// Fetch categories
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $categoryID = $_POST['categoryID'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $imageURL = $_POST['imageURL'];

    // Check if the category ID exists
    $stmt = $mysqli->prepare("SELECT CategoryID FROM Categories WHERE CategoryID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("i", $categoryID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO Products (ProductName, CategoryID, Price, Stock, Description, ImageURL) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($mysqli->error));
        }

        $stmt->bind_param("sidiss", $productName, $categoryID, $price, $stock, $description, $imageURL);

        if ($stmt->execute()) {
            $success = "Sản phẩm đã được đăng thành công.";
            header("Location: view_categories.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Category ID không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng sản phẩm mới</title>
    <link rel="stylesheet" type="text/css" href="../login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Đăng sản phẩm mới</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="add_product.php" method="post">
            <label for="productName">Tên sản phẩm:</label>
            <input type="text" id="productName" name="productName" required><br><br>
            <label for="categoryID">Danh mục:</label>
            <select id="categoryID" name="categoryID" required>
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['CategoryID']); ?>">
                        <?php echo htmlspecialchars($category['CategoryName']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            <label for="price">Giá:</label>
            <input type="number" step="0.01" id="price" name="price" required><br><br>
            <label for="stock">Số lượng:</label>
            <input type="number" id="stock" name="stock" required><br><br>
            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" required></textarea><br><br>
            <label for="imageURL">URL hình ảnh:</label>
            <input type="text" id="imageURL" name="imageURL"><br><br>
            <input type="submit" value="Đăng sản phẩm">
        </form>
    </div>
</body>
</html>