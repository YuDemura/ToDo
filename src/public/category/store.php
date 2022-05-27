<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$user_id = $_SESSION['user_id'];
$category_name = filter_input(INPUT_POST, 'category_name');

$sql = 'INSERT INTO categories(name, user_id) VALUES(:name, :user_id)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $category_name, PDO::PARAM_STR);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

header('Location: ./index.php');
exit();
