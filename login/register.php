<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập ở đây</a></p>
</body>
</html>

<?php
include('../admincp/config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $stmt = $mysqli->prepare("SELECT Email FROM Users WHERE Email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists, prompt user to log in
        echo "Email already exists. Please <a href='login.php'>login</a>.";
    } else {
        // Email does not exist, proceed with registration
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO Users (FullName, Email, PasswordHash, Role) VALUES (?, ?, ?, 'customer')");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($mysqli->error));
        }

        $stmt->bind_param("sss", $fullname, $email, $passwordHash);

        if ($stmt->execute()) {
            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>