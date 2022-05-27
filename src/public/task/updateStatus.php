<?php

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$task_id = filter_input(INPUT_GET, 'id');
$status = filter_input(INPUT_POST, 'status');

$sql = 'UPDATE tasks SET status=:status WHERE id=:task_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':status', $status, PDO::PARAM_INT);
$statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
$statement->execute();

header("Location: ../index.php");
exit();
?>
