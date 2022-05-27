<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
try {
    $pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$category_id = filter_input(INPUT_POST, 'id');
$category_name = filter_input(INPUT_POST, 'category_name');
$user_id = $_SESSION['user_id'];

$sql = 'UPDATE categories SET name=:name WHERE id =:category_id and user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':name', $category_name, PDO::PARAM_STR);
$statement->execute();

header("Location: ./index.php?id=$category_id");
exit();
?>
