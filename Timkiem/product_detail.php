<?php
include('../admincp/config/config.php');

$productID = '';
if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];
}

$product = null;
if ($productID) {
    $stmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($productID, $productName, $price, $stock, $description, $imageURL);
    if ($stmt->fetch()) {
        $product = [
            'ProductID' => $productID,
            'ProductName' => $productName,
            'Price' => $price,
            'Stock' => $stock,
            'Description' => $description,
            'ImageURL' => $imageURL
        ];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="./css/giaodine.css">
</head>
<body>
    <?php include('page/header.php'); ?>
    <div class="container">
        <?php if ($product): ?>
            <h2><?php echo htmlspecialchars($product['ProductName']); ?></h2>
            <p>Giá: <?php echo htmlspecialchars($product['Price']); ?></p>
            <p>Số lượng: <?php echo htmlspecialchars($product['Stock']); ?></p>
            <p>Mô tả: <?php echo htmlspecialchars($product['Description']); ?></p>
            <?php if ($product['ImageURL']): ?>
                <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
            <?php endif; ?>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm.</p>
        <?php endif; ?>
        <br>
        <a href="index.php" class="button">Quay lại trang chủ</a>
    </div>
</body>
</html>