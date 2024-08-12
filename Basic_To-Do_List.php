<?php
// File name
$filename = 'tasks.json';

// Read tasks from file
if (file_exists($filename)) {
    $tasks = json_decode(file_get_contents($filename), true);
} else {
    $tasks = [];
}

// Add task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && isset($_POST['priority'])) {
    $task = htmlspecialchars($_POST['task']);
    $priority = (int)$_POST['priority'];
    
    if ($priority < 1) $priority = 1;
    if ($priority > 100) $priority = 100;
    
    $tasks[] = ['task' => $task, 'completed' => false, 'priority' => $priority];
    file_put_contents($filename, json_encode($tasks));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Complete or delete task
if (isset($_GET['action']) && isset($_GET['index'])) {
    $index = (int)$_GET['index'];
    if ($_GET['action'] == 'complete') {
        $tasks[$index]['completed'] = !$tasks[$index]['completed'];
    } elseif ($_GET['action'] == 'delete') {
        unset($tasks[$index]);
        $tasks = array_values($tasks);
    }
    file_put_contents($filename, json_encode($tasks));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Sort tasks
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'none';
if ($sortOrder == 'priority') {
    usort($tasks, function($a, $b) {
        return $b['priority'] - $a['priority'];
    });
} else {
    $tasks = array_values($tasks);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
</head>
<body>
    <h1>Task List</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="task">New Task:</label>
        <input type="text" id="task" name="task" required>
        <br><br>

        <label for="priority">Priority (1 - 100):</label>
        <input type="number" id="priority" name="priority" min="1" max="100" required>
        <br><br>

        <input type="submit" value="Add">
    </form>

    <h2>Sorting Option</h2>
    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?sort=priority">Sort by Priority</a> | 
    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?sort=none">Remove Sorting</a>

    <ul>
        <?php foreach ($tasks as $index => $task): ?>
            <li>
                <span style="text-decoration: <?php echo $task['completed'] ? 'line-through' : 'none'; ?>">
                    <?php echo htmlspecialchars($task['task']); ?> (Priority: <?php echo $task['priority']; ?>)
                </span>
                <a href="?action=complete&index=<?php echo $index; ?>">
                    <?php echo $task['completed'] ? 'Mark as Incomplete' : 'Complete'; ?>
                </a>
                <a href="?action=delete&index=<?php echo $index; ?>" style="color: red;">
                    Delete
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
