<?php
include('../admincp/config/config.php');

$searchTerm = '';
if (isset($_GET['tukhoa'])) {
    $searchTerm = $_GET['tukhoa'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="./css/giaodine.css">
</head>
<body>
    <?php include('page/header.php'); ?>
    <div class="container">
        <h2>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
        <?php
        if ($searchTerm) {
            $stmt = $mysqli->prepare("SELECT ProductID, ProductName, Price, Stock, Description, ImageURL FROM Products WHERE ProductName LIKE ?");
            $searchTermWildcard = '%' . $searchTerm . '%';
            $stmt->bind_param("s", $searchTermWildcard);
            $stmt->execute();
            $stmt->bind_result($productID, $productName, $price, $stock, $description, $imageURL);
            $products = [];
            while ($stmt->fetch()) {
                $products[] = [
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
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p>Không tìm thấy sản phẩm nào.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <h4><a href="product_detail.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>"><?php echo htmlspecialchars($product['ProductName']); ?></a> (ID: <?php echo htmlspecialchars($product['ProductID']); ?>)</h4>
                        <p>Giá: <?php echo htmlspecialchars($product['Price']); ?></p>
                        <p>Số lượng: <?php echo htmlspecialchars($product['Stock']); ?></p>
                        <p>Mô tả: <?php echo htmlspecialchars($product['Description']); ?></p>
                        <?php if ($product['ImageURL']): ?>
                            <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>