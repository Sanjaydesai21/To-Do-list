<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: welcome.php');
    exit();
}

include('TodoConn.php');

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare('SELECT * FROM users WHERE id=:user_id');

$stmt->execute([':user_id' => $user_id]);

$user = $stmt->fetch();

if (!$user) {
    header('location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - To-Do List</title>
    <link rel="stylesheet" href="CSS/profile.css">
</head>

<body>
    <!-- nav bar -->
    <nav class="navbar">
        <div class="logo">My To-Do App</div>
        <input type="checkbox" id="toggle-menu" class="toggle-menu">
        <label for="toggle-menu" class="hamburger">&#9776;</label>
        <ul class="nav-links">
            <li><a href="NewToDo.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="help.php">Help</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>

        <button class="logout-btn"><a href="logout.php" id="log">Logout</a></button>
    </nav>

    <!-- Profile start -->
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-img-container">
                <img src="https://via.placeholder.com/150" alt="Profile Image" class="profile-img">
            </div>
            <h1 class="username"><?php echo htmlspecialchars($user['username']) ?></h1>
            <p class="email"><?php echo htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="profile-body">
            <h2 class="section-title">To-Do List Summary</h2>
            <div class="todo-summary">
                <div class="summary-item">
                    <p><strong>Total Tasks:</strong>
                        <?php
                        $task_stmt = $db->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id');
                        $task_stmt->execute([':user_id' => $user_id]);
                        $total_task = $task_stmt->fetchColumn();
                        echo $total_task;
                        ?></p>
                </div>
                <div class="summary-item">
                    <p><strong>Completed:</strong>
                        <?php
                        $completed_stmt = $db->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND completed = 1');
                        $completed_stmt->execute([':user_id' => $user_id]);
                        $completed_task = $completed_stmt->fetchColumn();
                        echo $completed_task;
                        ?></p>
                </div>
                <div class="summary-item">
                    <p><strong>Pending:</strong>
                        <?php
                        $pending_stmt = $db->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND completed = 0');
                        $pending_stmt->execute([':user_id' => $user_id]);
                        $pending_task = $pending_stmt->fetchColumn();
                        echo $pending_task;
                        ?></p>
                </div>
            </div>

            <div class="task-actions">
                <button class="task-button" onclick="window.location.href='NewToDo.php'">Go to list</button>

            </div>
        </div>
    </div>
</body>

</html>