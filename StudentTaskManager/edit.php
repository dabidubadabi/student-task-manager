<?php
session_start();
require "db.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    die("Task not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $category = trim($_POST["category"]);
    $priority = $_POST["priority"];
    $due_date = $_POST["due_date"];
    $completed = isset($_POST["completed"]) ? 1 : 0;

    if ($title == "" || $category == "" || $due_date == "") {
        $error = "Please fill in all fields.";
    } else {
        $sql = "UPDATE tasks
                SET title = ?, category = ?, priority = ?,
                    due_date = ?, completed = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $title,
            $category,
            $priority,
            $due_date,
            $completed,
            $id
        ]);

        $_SESSION["message"] = "Task updated.";
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Task</h1>

    <p class="error"><?= $error ?></p>

    <form method="post">
        <label>Title</label>
        <input type="text" name="title"
               value="<?= htmlspecialchars($task["title"]) ?>">

        <label>Category</label>
        <input type="text" name="category"
               value="<?= htmlspecialchars($task["category"]) ?>">

        <label>Priority</label>
        <select name="priority">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date"
               value="<?= htmlspecialchars($task["due_date"]) ?>">

        <label>
            <input type="checkbox" name="completed"
                <?= $task["completed"] ? "checked" : "" ?>>
            Completed
        </label>

        <button type="submit">Update</button>
    </form>

    <a href="index.php">Back</a>
</body>
</html>