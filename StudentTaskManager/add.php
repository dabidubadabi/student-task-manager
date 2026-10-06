<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $category = trim($_POST["category"]);
    $priority = $_POST["priority"];
    $due_date = $_POST["due_date"];

    $priorities = ["Low", "Medium", "High"];

    if ($title == "" || $category == "" || $due_date == "") {
        $error = "Please fill in all fields.";
    } elseif (!in_array($priority, $priorities)) {
        $error = "Invalid priority.";
    } else {
        $sql = "INSERT INTO tasks (title, category, priority, due_date)
                VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $priority, $due_date]);

        $_SESSION["message"] = "Task added.";
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Add Task</h1>

    <p class="error"><?= $error ?></p>

    <form method="post">
        <label>Title</label>
        <input type="text" name="title">

        <label>Category</label>
        <input type="text" name="category">

        <label>Priority</label>
        <select name="priority">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date">

        <button type="submit">Save</button>
    </form>

    <a href="index.php">Back</a>
</body>
</html>