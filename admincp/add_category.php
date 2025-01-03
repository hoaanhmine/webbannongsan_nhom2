<?php


include('config/config.php');

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST['categoryName'];

    $stmt = $mysqli->prepare("INSERT INTO Categories (CategoryName) VALUES (?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("s", $categoryName);

    if ($stmt->execute()) {
        $success = "Danh mục đã được thêm thành công.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm danh mục mới</title>
    <link rel="stylesheet" type="text/css" href="../login/styles.css">
</head>
<body>
    <div class="container">
        <h2>Thêm danh mục mới</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="add_category.php" method="post">
            <label for="categoryName">Tên danh mục:</label>
            <input type="text" id="categoryName" name="categoryName" required><br><br>
            <input type="submit" value="Thêm danh mục">
        </form>
    </div>
</body>
</html>