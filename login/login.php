<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

<?php
include('../admincp/config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT UserID, PasswordHash FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userID, $passwordHash);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $passwordHash)) {
        // Start a session and store user information
        session_start();
        $_SESSION['userID'] = $userID;
        $_SESSION['email'] = $email;
        // Redirect to the homepage
        header("Location: ../index.php");
        exit();
    } else {
        echo "Sai email hoặc mật khẩu.";
    }

    $stmt->close();
}
?>