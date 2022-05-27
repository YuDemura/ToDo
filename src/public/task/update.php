<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
try {
    $pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$task_id = filter_input(INPUT_POST, 'id');
$category_id = filter_input(INPUT_POST, 'category_id');
$contents = filter_input(INPUT_POST, 'contents');
$deadline = filter_input(INPUT_POST, 'deadline');

$sql = 'UPDATE tasks SET contents=:contents, category_id=:category_id, deadline=:deadline WHERE id=:task_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':contents', $contents, PDO::PARAM_STR);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
$statement->execute();

header("Location: ../index.php");
exit();
?>
