<?php
session_start();
require "db.php";

// Get selected priority filter from query string
$priority_filter = isset($_GET["priority"]) ? $_GET["priority"] : "All";
$valid_priorities = ["All", "Low", "Medium", "High"];

if (!in_array($priority_filter, $valid_priorities)) {
    $priority_filter = "All";
}

// Build query based on filter
if ($priority_filter === "All") {
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY due_date ASC, id DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE priority = ? ORDER BY due_date ASC, id DESC");
    $stmt->execute([$priority_filter]);
}
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Student Task Manager</h1>

    <div class="controls">
        <a href="add.php" class="btn">Add Task</a>

        <form method="get" class="filter-form">
            <label for="priority">Filter by Priority:</label>
            <select name="priority" id="priority" onchange="this.form.submit()">
                <option value="All" <?= $priority_filter === "All" ? "selected" : "" ?>>All</option>
                <option value="Low" <?= $priority_filter === "Low" ? "selected" : "" ?>>Low</option>
                <option value="Medium" <?= $priority_filter === "Medium" ? "selected" : "" ?>>Medium</option>
                <option value="High" <?= $priority_filter === "High" ? "selected" : "" ?>>High</option>
            </select>
        </form>
    </div>

    <?php if (isset($_SESSION["message"])): ?>
        <p class="message">
            <?= htmlspecialchars($_SESSION["message"]) ?>
        </p>
        <?php unset($_SESSION["message"]); ?>
    <?php endif; ?>

    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tasks as $task): ?>
            <tr class="priority-<?= strtolower($task["priority"]) ?>">
                <td><?= htmlspecialchars($task["title"]) ?></td>
                <td><?= htmlspecialchars($task["category"]) ?></td>
                <td><?= htmlspecialchars($task["priority"]) ?></td>
                <td><?= htmlspecialchars($task["due_date"]) ?></td>
                <td>
                    <?php if ($task["completed"]): ?>
                        <span class="status-done">Done</span>
                    <?php else: ?>
                        <span class="status-not-done">Not done</span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="edit.php?id=<?= $task["id"] ?>" class="btn-small">Edit</a>
                    <a href="delete.php?id=<?= $task["id"] ?>" class="btn-small btn-delete">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>