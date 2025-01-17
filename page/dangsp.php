<?php
session_start(); // Khởi tạo session

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

// Handle product addition to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $productID = $_POST['productID'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT ProductID, ProductName, Price FROM Products WHERE ProductID = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($productID, $productName, $price);
    if ($stmt->fetch()) {
        $product = [
            'ProductID' => $productID,
            'ProductName' => $productName,
            'Price' => $price,
            'Quantity' => 1
        ];

        // Thêm sản phẩm vào giỏ hàng
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['ProductID'] == $productID) {
                $item['Quantity']++;
                $found = true;
                break;
            }
        }

        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        if (!$found) {
            $_SESSION['cart'][] = $product;
        }

        $success = "Sản phẩm đã được thêm vào giỏ hàng.";
    }
    $stmt->close();
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

$categoryStmt = $conn->prepare("SELECT CategoryID, CategoryName FROM Categories");
if ($categoryStmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$categoryStmt->execute();
$categoryStmt->bind_result($categoryID, $categoryName);
$categories = [];
while ($categoryStmt->fetch()) {
    $categories[] = ['CategoryID' => $categoryID, 'CategoryName' => $categoryName];
}
$categoryStmt->close();

$productStmt = $conn->prepare("SELECT ProductID, ProductName, ImageURL, Price FROM Products");
if ($productStmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$productStmt->execute();
$productStmt->bind_result($productID, $productName, $imageURL, $price);
$products = [];
while ($productStmt->fetch()) {
    $products[] = ['ProductID' => $productID, 'ProductName' => $productName, 'ImageURL' => $imageURL, 'Price' => $price];
}
$productStmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sản phẩm</title>
    <style>
        .dangsp-body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .dangsp-container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .dangsp-sanpham {
            text-align: center;
            padding: 20px;
            background: #4CAF50; 
            color: #fff;
            margin-bottom: 20px;
        }
        .dangsp-product-slider {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .dangsp-danhmucsp {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .dangsp-sp {
            background: #fff;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            width: 23%; /* 4 sản phẩm một dòng */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .dangsp-sp img {
            max-width: 100%;
            height: auto;
        }
        .dangsp-sp h3 {
            font-size: 18px;
            color: #333;
        }
        .dangsp-sp p {
            font-size: 16px;
            color: #666;
        }
        .dangsp-sp button {
            background: #4CAF50; /* Màu xanh lá cây */
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
            border-radius: 5px; 
        }
        .dangsp-sp button:hover {
            background: #45a049; 
        }
        .dangsp-sp:hover {
            transform: scale(1.05);
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
<body class="dangsp-body">
    <div class="dangsp-sanpham">Ẩm thực</div>
    <section class="dangsp-products dangsp-container">
        <div class="dangsp-product-slider">
            <div class="dangsp-danhmucsp">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="dangsp-sp">
                            <a href="./page/hienthisanpham.php?id=<?php echo $product['ProductID']; ?>">
                                <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                            </a>
                            <h3><?php echo $product['ProductName']; ?></h3>
                            <p>Giá: <?php echo number_format($product['Price'], 0, ',', '.'); ?> VND</p>
                            <form method="post" action="">
                                <input type="hidden" name="productID" value="<?php echo $product['ProductID']; ?>">
                                <input type="hidden" name="action" value="add_to_cart">
                                <button type="submit">Thêm vào giỏ hàng</button>
                            </form>
                            <button onclick="location.href='./page/checkout.php?id=<?php echo $product['ProductID']; ?>'">Mua hàng</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>

<?php
$conn->close();
?>