<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nogsan"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

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

    unset($_SESSION['cart']);
}

$conn->close();
?>