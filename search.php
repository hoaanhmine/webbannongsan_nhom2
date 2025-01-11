<?php
include('./admincp/config/config.php');

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
    <style>
        .container {
            width: 80%;
            margin: auto;
            font-family: Arial, sans-serif;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .product {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            width: 23%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .product h4 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        .product p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .product:hover {
            transform: scale(1.05);
        }
    </style>
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
                        <h4><a href="./page/hienthisanpham.php?id=<?php echo htmlspecialchars($product['ProductID']); ?>"><?php echo htmlspecialchars($product['ProductName']); ?></a></h4>
                        <p>Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VND</p>
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