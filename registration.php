<?php

include('TodoConn.php');

$registration_success = false;
$registration_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $registration_error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password, normal_password) VALUES (:username, :email, :password,:normal_password)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':normal_password'=>$password
            ]);
            $registration_success = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { 
                $registration_error = "Error: Username or email already exists.";
            } else {
                $registration_error = "An error occurred: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="CSS/reg.css">
</head>

<body>
    <div class="registration-container">
        <h2>Register</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <div>
                <?php
                if ($registration_success) {
                    echo "<span style='color: green;>Registration successful! <a href='login.php'>Login here</a></span>";
                } elseif ($registration_error) {
                    echo "<span style='color: red;'>" . htmlspecialchars($registration_error) . "</span>";
                }
                ?>
            </div>
            <div class="account">
                Already have an account? <a href="login.php">Login Here</a>
            </div>
        </form>
    </div>
</body>

</html>