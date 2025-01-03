<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: ../login/login.php");
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
    <link rel="stylesheet" type="text/css" href="../login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Chào mừng, <?php echo htmlspecialchars($fullName); ?>!</h2>
        <div class="user-info">
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Vai trò: <?php echo htmlspecialchars($role); ?></p>
            <p>Trạng thái: <?php echo htmlspecialchars($status); ?></p>
            <p>Ngày tạo tài khoản: <?php echo htmlspecialchars($createdAt); ?></p>
        </div>
        <a href="../login/logout.php">Đăng xuất</a>
        <a href="../index.php">Trang chủ</a>

        <h2>Chỉnh sửa thông tin</h2>
        <form action="index.php" method="post">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullName); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="password">Mật khẩu mới (để trống nếu không thay đổi):</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Update">
        </form>

        <?php if ($role === 'admin'): ?>
            <h2>Quản lý sản phẩm</h2>
            <a href="../admincp/add_product.php">Đăng sản phẩm mới</a>
            <h2>Quản lý danh mục</h2>
            <a href="../admincp/add_category.php">Thêm danh mục mới</a>
            <h2>Xem danh mục và sản phẩm</h2>
            <a href="../admincp/view_categories.php">Xem danh mục và sản phẩm</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle user information update
    $newFullName = $_POST['fullname'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    if (!empty($newPassword)) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $mysqli->prepare("UPDATE Users SET FullName = ?, Email = ?, PasswordHash = ? WHERE UserID = ?");
        $stmt->bind_param("sssi", $newFullName, $newEmail, $newPasswordHash, $userID);
    } else {
        $stmt = $mysqli->prepare("UPDATE Users SET FullName = ?, Email = ? WHERE UserID = ?");
        $stmt->bind_param("ssi", $newFullName, $newEmail, $userID);
    }

    if ($stmt->execute()) {
        echo "Thông tin đã được cập nhật.";
        // Update session email if changed
        $_SESSION['email'] = $newEmail;
        // Refresh the page to show updated information
        header("Refresh:0");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>