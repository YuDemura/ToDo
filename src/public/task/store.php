<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$user_id = $_SESSION['user_id'];
$contents = filter_input(INPUT_POST, 'contents');
$deadline = filter_input(INPUT_POST, 'deadline');
$category_id = filter_input(INPUT_POST, 'category_id');

$sql = 'INSERT INTO tasks(user_id, status, contents, category_id, deadline) VALUES(:user_id, 0, :contents, :category_id, :deadline)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':contents', $contents, PDO::PARAM_STR);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$result = $statement->execute();

header('Location: ../index.php');
exit();
