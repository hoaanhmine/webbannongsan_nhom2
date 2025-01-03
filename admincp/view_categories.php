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

// Handle product search
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow-y: auto;
            max-height: 90vh;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .category, .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .category h3, .product h4 {
            margin: 0 0 10px 0;
        }
        .product img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 10px auto;
        }
        .actions {
            text-align: center;
        }
        .actions a, .actions form {
            display: inline-block;
            margin: 0 5px;
        }
        .actions a {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
        .actions input[type="submit"] {
            background-color: #dc3545;
        }
        .actions input[type="submit"]:hover {
            background-color: #c82333;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .product {
            flex: 1 1 calc(50% - 10px);
            box-sizing: border-box;
        }
    </style>
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
        <form action="view_categories.php" method="get">
            <label for="search">Tìm kiếm sản phẩm:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <input type="submit" value="Tìm kiếm">
        </form>
        <?php foreach ($categories as $category): ?>
            <div class="category">
                <h3><?php echo htmlspecialchars($category['CategoryName']); ?> (ID: <?php echo htmlspecialchars($category['CategoryID']); ?>)</h3>
                <div class="actions">
                    <form action="view_categories.php" method="post" style="display:inline;">
                        <input type="hidden" name="action" value="delete_category">
                        <input type="hidden" name="categoryID" value="<?php echo htmlspecialchars($category['CategoryID']); ?>">
                        <input type="submit" value="Xóa">
                    </form>
                    <a href="edit_category.php?categoryID=<?php echo htmlspecialchars($category['CategoryID']); ?>">Sửa</a>
                </div>
                <?php
                // Fetch products for this category
                if ($searchTerm) {
                    $productStmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE CategoryID = ? AND ProductName LIKE ?");
                    $searchTermWildcard = '%' . $searchTerm . '%';
                    $productStmt->bind_param("is", $category['CategoryID'], $searchTermWildcard);
                } else {
                    $productStmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE CategoryID = ?");
                    $productStmt->bind_param("i", $category['CategoryID']);
                }
                if ($productStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                }
                $productStmt->execute();
                $productStmt->bind_result($productID, $productName, $price, $stock, $description, $imageURL);
                ?>
                <div class="product-grid">
                    <?php while ($productStmt->fetch()): ?>
                        <div class="product">
                            <h4><?php echo htmlspecialchars($productName); ?> (ID: <?php echo htmlspecialchars($productID); ?>)</h4>
                            <p>Giá: <?php echo htmlspecialchars($price); ?></p>
                            <p>Số lượng: <?php echo htmlspecialchars($stock); ?></p>
                            <p>Mô tả: <?php echo htmlspecialchars($description); ?></p>
                            <?php if ($imageURL): ?>
                                <img src="<?php echo htmlspecialchars($imageURL); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
                            <?php endif; ?>
                            <div class="actions">
                                <form action="view_categories.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_product">
                                    <input type="hidden" name="productID" value="<?php echo htmlspecialchars($productID); ?>">
                                    <input type="submit" value="Xóa">
                                </form>
                                <a href="edit_product.php?productID=<?php echo htmlspecialchars($productID); ?>">Sửa</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php $productStmt->close(); ?>
            </div>
        <?php endforeach; ?>
        <br>
        <a href="../login/index.php" class="button">Trang thông tin</a>
    </div>
</body>
</html>