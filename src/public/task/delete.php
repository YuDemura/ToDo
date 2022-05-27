<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword
);

$task_id = filter_input(INPUT_GET, 'id');
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM tasks where id =:task_id and user_id=:user_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

header('Location: ../index.php');
exit();
