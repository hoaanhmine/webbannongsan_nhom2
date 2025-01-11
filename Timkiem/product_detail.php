<?php
session_start();

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nogsan"; // Đảm bảo tên cơ sở dữ liệu là chính xác

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$productID = isset($_GET['ProductID']) ? intval($_GET['ProductID']) : 0;
$product = null;

if ($productID > 0) {
    $stmt = $conn->prepare("SELECT ProductID, ProductName, ImageURL, Price, Description FROM Products WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($productID, $productName, $imageURL, $price, $description);
    if ($stmt->fetch()) {
        $product = [
            'ProductID' => $productID,
            'ProductName' => $productName,
            'ImageURL' => $imageURL,
            'Price' => $price,
            'Description' => $description
        ];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết sản phẩm</title>
    <style>
        .product-detail-container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-detail {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product-detail img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
        }
        .product-detail-info {
            flex: 1;
            margin-left: 20px;
        }
        .product-detail-info h1 {
            font-size: 24px;
            color: #333;
        }
        .product-detail-info p {
            font-size: 18px;
            color: #666;
        }
        .product-detail-info .price {
            font-size: 20px;
            color: #4CAF50;
            margin-top: 10px;
        }
        .product-detail-info button {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
            border-radius: 5px;
        }
        .product-detail-info button:hover {
            background: #45a049;
        }
        .back-button {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="product-detail-container">
        <?php if ($product): ?>
            <div class="product-detail">
                <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                <div class="product-detail-info">
                    <h1><?php echo $product['ProductName']; ?></h1>
                    <p><?php echo $product['Description']; ?></p>
                    <p class="price">Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VND</p>
                    <button onclick="location.href='./page/checkout.php?id=<?php echo $product['ProductID']; ?>'">Mua hàng</button>
                    <a href="../index.php" class="back-button">Về trang chủ</a>
                </div>
            </div>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm.</p>
        <?php endif; ?>
    </div>
</body>
</html>