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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // Thêm logic để xử lý thông tin thanh toán, ví dụ: lưu vào cơ sở dữ liệu
    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO Orders (ProductID, Name, Email, Phone, Address, Quantity) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("issssi", $item['ProductID'], $name, $email, $phone, $address, $item['Quantity']);
        if ($stmt->execute()) {
            echo "Đặt hàng thành công!";
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    }

    // Xóa giỏ hàng sau khi thanh toán thành công
    unset($_SESSION['cart']);
}

$conn->close();
?>