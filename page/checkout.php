<?php
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
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;
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
    <title>Thanh toán</title>
    <style>
        .checkout-container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .checkout-detail {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .checkout-detail img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
        }
        .checkout-detail-info {
            flex: 1;
            margin-left: 20px;
        }
        .checkout-detail-info h1 {
            font-size: 24px;
            color: #333;
        }
        .checkout-detail-info p {
            font-size: 18px;
            color: #666;
        }
        .checkout-detail-info .price {
            font-size: 20px;
            color: #4CAF50;
            margin-top: 10px;
        }
        .checkout-form {
            margin-top: 20px;
        }
        .checkout-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .checkout-form input[type="text"],
        .checkout-form input[type="email"],
        .checkout-form input[type="tel"],
        .checkout-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .checkout-form button {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
            border-radius: 5px;
        }
        .checkout-form button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <?php if ($product): ?>
            <div class="checkout-detail">
                <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                <div class="checkout-detail-info">
                    <h1><?php echo $product['ProductName']; ?></h1>
                    <p><?php echo $product['Description']; ?></p>
                    <p class="price">Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VND</p>
                </div>
            </div>
            <div class="checkout-form">
                <form method="post" action="process_checkout.php">
                    <input type="hidden" name="productID" value="<?php echo $product['ProductID']; ?>">
                    <label for="name">Tên:</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" required>
                    
                    <label for="address">Địa chỉ:</label>
                    <textarea id="address" name="address" required></textarea>
                    
                    <button type="submit">Thanh toán</button>
                </form>
            </div>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm.</p>
        <?php endif; ?>
    </div>
</body>
</html>