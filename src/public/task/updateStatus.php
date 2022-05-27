<?php

require_once __DIR__ . '/../utils/pdo.php';

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
