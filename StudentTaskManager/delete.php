<?php
session_start();
require "db.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->execute([$id]);

$_SESSION["message"] = "Task deleted.";

header("Location: index.php");
exit;
?>