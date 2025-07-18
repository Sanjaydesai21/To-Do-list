<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - To-Do List Application</title>
    <link rel="stylesheet" href="CSS/help.css"> <!-- Link to your CSS -->
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
            <h1>Help & Support</h1>
        </div>
    </header>
    <main>
        <section class="help-section">
            <h2>Getting Started</h2>
            <p>
                Welcome to the To-Do List Application! This section will help you get started and answer common questions
                about using the app.
            </p>
        </section>

        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div class="faq">
                <h3>1. How do I create a new task?</h3>
                <p>Go to the main dashboard and click on the "Add Task" button. Fill in the details of your task, and it will be added to your list.</p>
            </div>
            <div class="faq">
                <h3>2. How do I edit or delete a task?</h3>
                <p>On your task list, you'll find edit and delete buttons next to each task. Click the edit button to make changes, or delete to remove the task.</p>
            </div>
            <div class="faq">
                <h3>3. Can I search for tasks?</h3>
                <p>Yes, use the search bar on the dashboard to quickly find tasks by entering relevant keywords.</p>
            </div>
            <div class="faq">
                <h3>4. How do I mark a task as completed?</h3>
                <p>Click on the checkbox next to a task to mark it as completed. Completed tasks are moved to a separate section for better tracking.</p>
            </div>
            <div class="faq">
                <h3>5. How do I update my profile information?</h3>
                <p>Go to the profile page and click on the "Edit Profile" button. Update the information and save the changes.</p>
            </div>
        </section>

        <section class="contact-section">
            <h2>Contact Support</h2>
            <p>
                If you have further questions or need help, feel free to reach out to us at
                <a href="mailto:support@todolistapp.com">support@todolistapp.com</a>.
            </p>
            <p>You can also visit our <a href="about.php">About Page</a> for more details about the application.</p>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> To-Do List Application. All rights reserved.</p>
    </footer>
</body>

</html>