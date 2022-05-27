<?php
session_start();
$_SESSION['error'] = '';

$user_id = $_SESSION['user_id'];
$contents = filter_input(INPUT_POST, 'contents');
$deadline = filter_input(INPUT_POST, 'deadline');
$category_id = filter_input(INPUT_POST, 'category_id');

if (empty($_POST['contents']) && !empty($_POST['deadline'])) {
    $_SESSION['error'] = 'タスク内容または日付を入力してください';
    header('Location: ./create.php');
    exit();
}

require_once __DIR__ . '/../utils/pdo.php';
$sql = 'INSERT INTO tasks(user_id, contents, category_id, deadline) VALUES(:user_id, :contents, :category_id, :deadline)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':contents', $contents, PDO::PARAM_STR);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$result = $statement->execute();

header('Location: ../index.php');
exit();
