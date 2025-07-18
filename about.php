<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - To-Do List Application</title>
    <link rel="stylesheet" href="CSS/about.css"> <!-- Link to your CSS -->
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

    <header>
        <div class="header-container">
            <h1>About Our To-Do List Application</h1>
        </div>
    </header>
    <main>
        <section class="about-section">
            <h2>Welcome!</h2>
            <p>
                Our To-Do List Application is your ultimate tool for staying organized and managing your daily tasks. 
                Whether you're planning your day, tracking work projects, or managing your personal goals, our application 
                is designed to help you stay on top of your tasks.
            </p>
        </section>

        <section class="features-section">
            <h2>Features</h2>
            <ul>
                <li>Add, edit, and delete tasks effortlessly.</li>
                <li>Mark tasks as completed to track your progress.</li>
                <li>Search for tasks using keywords.</li>
                <li>Secure user authentication to protect your data.</li>
                <li>Clean and user-friendly interface.</li>
            </ul>
        </section>

        <section class="purpose-section">
            <h2>Our Purpose</h2>
            <p>
                We believe that an organized life is a productive life. Our goal is to make task management simple, 
                accessible, and effective for everyone. With features tailored for ease of use, this application helps you 
                focus on what matters most.
            </p>
        </section>

        <section class="contact-section">
            <h2>Contact Us</h2>
            <p>
                Have feedback or suggestions? We'd love to hear from you! Reach out at 
                <a href="mailto:support@todolistapp.com">support@todolistapp.com</a>.
            </p>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> To-Do List Application. All rights reserved.</p>
    </footer>
</body>
</html>
