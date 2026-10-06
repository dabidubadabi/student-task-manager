<?php
session_start();
require "db.php";

$tasks = $pdo->query("SELECT * FROM tasks ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Student Task Manager</h1>

    <a href="add.php">Add Task</a>

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
            <tr>
                <td><?= htmlspecialchars($task["title"]) ?></td>
                <td><?= htmlspecialchars($task["category"]) ?></td>
                <td><?= htmlspecialchars($task["priority"]) ?></td>
                <td><?= htmlspecialchars($task["due_date"]) ?></td>
                <td>
                    <?= $task["completed"] ? "Done" : "Not done" ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $task["id"] ?>">Edit</a>
                    <a href="delete.php?id=<?= $task["id"] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>