<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$category_id = filter_input(INPUT_GET, 'id');
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM categories where id=:category_id and user_id=:user_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

header('Location: ./index.php');
exit();
