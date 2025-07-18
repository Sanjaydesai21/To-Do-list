<?php
session_start();


if (!isset($_SESSION['user_id'])) {
  header("location: welcome.php");
  exit();
}

// Include the database connection file
include("ToDoConn.php"); // Ensure this establishes a PDO connection

$edit_task_id = null; // Initialize the variable
$edit_task_description = '';

$user_id = $_SESSION['user_id'];

// Add Task Logic
if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
  $task = trim($_POST['task']);

  // Check if this is an edit request (check if task_id is set)
  if (isset($_POST['task_id']) && $_POST['task_id']) {
    $task_id = $_POST['task_id'];

    // Update the task if task_id is provided
    $stmt = $db->prepare("UPDATE tasks SET task_description = :task_description , created_at = NOW() WHERE id = :id AND user_id = :user_id");
    if ($stmt->execute([':task_description' => $task, ':id' => $task_id, ':user_id' => $user_id])) {
      header("Location: NewToDo.php");
      exit();
    } else {
      echo "Task not updated.";
    }
  } else {
    // Add a new task if no task_id is provided
    $stmt = $db->prepare("INSERT INTO tasks (task_description, user_id) VALUES (:task_description, :user_id)");
    $stmt->execute([':task_description' => $task, ':user_id' => $user_id]);
  }
}



// Delete task

if (isset($_POST['delete'])) {
  $delete = $_POST['delete'];

  // Validate that the id is a valid integer
  if (filter_var($delete, FILTER_VALIDATE_INT)) {
    // Use a parameterized query to prevent SQL injection
    $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
    if ($stmt->execute([':id' => $delete, ':user_id' => $user_id])) {

      header("location:NewToDo.php");
    } else {
      echo "Task not deleted.";
    }
  } else {
    echo "Invalid task ID.";
  }
}


// Edit Task Logic
if (isset($_POST['edit'])) {
  $edit_task_id = $_POST['edit'];

  // Fetch the task description for the task being edited
  $stmt = $db->prepare("SELECT task_description FROM tasks WHERE id = :id AND user_id = :user_id");
  $stmt->execute([':id' => $edit_task_id, 'user_id' => $user_id]);
  $task = $stmt->fetch();

  // Pre-fill the task description into the input field
  if ($task) {
    $edit_task_description = $task['task_description'];
  }
}

// Search Logic 
$search_term = '';
if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
  $search_term = trim($_POST['search']);
}


// Handle task completion status update
if (isset($_POST['task_id'])) {
  $task_id = $_POST['task_id'];
  $completed = isset($_POST['completed']) ? 1 : 0;

  $stmt = $db->prepare("UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id");
  $stmt->execute([':completed' => $completed, ':id' => $task_id, ':user_id' => $user_id]);
}

// Task filtering logic

$filter = 'all';
if (isset($_POST['filter'])) {
  $filter = $_POST['filter'];
}

// Fetch Tasks
$tasks = [];
if ($search_term) {
  $stmt = $db->prepare("SELECT id, task_description, created_at, completed 
                          FROM tasks 
                          WHERE task_description LIKE :search_term AND user_id = :user_id
                          ORDER BY created_at DESC");
  $stmt->execute([':search_term' => "%$search_term%", ':user_id' => $user_id]);
} elseif ($filter !== 'all') {
  $stmt = $db->prepare("SELECT id, task_description, created_at, completed 
                          FROM tasks 
                          WHERE completed = :completed AND user_id = :user_id
                          ORDER BY created_at DESC");
  $stmt->execute([':completed' => ($filter === 'completed' ? 1 : 0), ':user_id' => $user_id]);
} else {
  $stmt = $db->prepare("SELECT id, task_description, created_at, completed 
                          FROM tasks 
                          WHERE user_id = :user_id
                          ORDER BY created_at DESC");
  $stmt->execute([':user_id' => $user_id]);
}
$tasks = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To-Do List</title>
  <link rel="stylesheet" href="CSS/todo.css">

  <style>
    /* Dropdown Filter Styling */
    .filter-section {
      margin: 10px 0;
      text-align: center;
    }

    .filter-section select {
      padding: 8px 12px;
      border-radius: 5px;
      border: 1px solid #ddd;
      font-size: 1rem;
      cursor: pointer;
      background-color: #f4f4f4;
      transition: border-color 0.3s ease;
    }

    .filter-section select:focus {
      border-color: #3498db;
      outline: none;
    }

    /* Task Checkbox Styling */
    .task-checkbox {
      display: flex;
      align-items: center;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
      margin: 8px 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .task-checkbox:hover {
      background-color: #f0f8ff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Form Styling */
    .task-checkbox form {
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
    }

    /* Label Styling */
    .task-checkbox label {
      font-size: 16px;
      color: #333;
      cursor: pointer;
      margin-left: 8px;
      display: flex;
      align-items: center;
    }

    /* Checkbox Styling */
    .task-checkbox input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #4caf50;
      cursor: pointer;
      margin-right: 5px;
    }

    /* Checked State Styling */
    .task-checkbox input[type="checkbox"]:checked+span {
      text-decoration: line-through;
      color: #888;
    }

    .task-text .completed {
      text-decoration: line-through;
      color: green;
    }

    .task-checkbox input[type="checkbox"]:checked {
      accent-color: green;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .filter-section select {
        font-size: 0.9rem;
        padding: 6px 10px;
      }

      .task-checkbox {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        gap: 5px;
      }

      .task-checkbox label {
        font-size: 14px;
      }

      .task-checkbox input[type="checkbox"] {
        width: 16px;
        height: 16px;
      }
    }

    @media (max-width: 480px) {
      .filter-section select {
        width: 100%;
        font-size: 0.85rem;
        padding: 6px;
      }

      .task-checkbox {
        flex-direction: column;
        padding: 8px;
      }

      .task-checkbox label {
        font-size: 12px;
      }

      .task-checkbox input[type="checkbox"] {
        width: 14px;
        height: 14px;
      }
    }
  </style>

</head>

<body>

  <!-- Nav bar -->


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
    <div class="username"> User: <?php echo htmlspecialchars($_SESSION['username']) ?></div>
    <button class="logout-btn"><a href="logout.php" id="log">Logout</a></button>
  </nav>

  <form action="" method="post">
    <div class="todo-container">
      <h1>To-Do List</h1>


      <div class="add-search">
        <!-- Add Task Section -->
        <div class="add-task">
          <input type="text" id="task-input" name="task" placeholder="Add a new task..." value="<?php echo htmlspecialchars($edit_task_description); ?>" autofocus />
          <input type="hidden" name="task_id" value="<?php echo $edit_task_id; ?>" />
          <button id="add-task-btn" name="add-task-btn">
            <?php echo $edit_task_id ? 'Edit Task' : 'Add Task'; ?>
          </button>
        </div>


        <!-- Search Bar -->
        <div class="search-bar">
          <input type="text" id="search-input" name="search" placeholder="Search tasks..." />
          <button class="search-btn">Search</button>
        </div>

      </div>

      <!-- Filter Dropdown -->
      <div class="filter-section">
        <select name="filter" id="filter" onchange="this.form.submit()">
          <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All</option>
          <option value="pending" <?php echo $filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
          <option value="completed" <?php echo $filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>
      </div>



      <!-- Task List -->
      <ul id="task-list">
        <?php if (count($tasks) > 0): ?>
          <?php foreach ($tasks as $task): ?>
            <li class="text-wrap">

              <!-- Row with Edit, Task, and Delete -->
              <div class="task-row">
                <form action="" method="post" style="display:inline;">
                  <button name="edit" value="<?php echo htmlspecialchars($task['id']); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z" />
                    </svg></button>
                </form>

                <div class="task-text" style="word-break: break-all;">
                  <span class="<?php echo $task['completed'] ? 'completed' : ''; ?>">
                    <?php echo htmlspecialchars(ucfirst($task['task_description'])); ?>
                  </span>

                </div>


                <form action="" method="post" style="display:inline;">
                  <button name="delete" value="<?php echo htmlspecialchars($task['id']); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z" />
                    </svg></button>
                </form>
              </div>
              <!-- Checkbox to mark task as completed -->
              <div class="task-checkbox">
                <form action="" method="post">
                  <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                  <label>
                    <input
                      type="checkbox"
                      name="completed"
                      <?php echo $task['completed'] ? 'checked' : ''; ?>
                      onchange="this.form.submit();">
                    Mark as Completed
                  </label>
                </form>
              </div>
              <hr style="color:#333; width:100%;">
              <!-- Timestamp -->
              <div class="timestamp">
                <?php echo htmlspecialchars($task['created_at']); ?>
              </div>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No tasks found.</li>
        <?php endif; ?>
      </ul>

    </div>
  </form>
</body>

</html>