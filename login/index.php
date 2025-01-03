<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

include('../admincp/config/config.php');

// Fetch user information from the database
$userID = $_SESSION['userID'];
$stmt = $mysqli->prepare("SELECT FullName, Email, Role, Status, CreatedAt FROM Users WHERE UserID = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($fullName, $email, $role, $status, $createdAt);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thông tin người dùng</title>
</head>
<body>
    <h2>Chào mừng, <?php echo htmlspecialchars($fullName); ?>!</h2>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Vai trò: <?php echo htmlspecialchars($role); ?></p>
    <p>Trạng thái: <?php echo htmlspecialchars($status); ?></p>
    <p>Ngày tạo tài khoản: <?php echo htmlspecialchars($createdAt); ?></p>
    <a href="../login/logout.php">Đăng xuất</a>
</body>
</html>