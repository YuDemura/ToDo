<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$user_id = $_SESSION['user_id'];
$category_id = filter_input(INPUT_GET, 'id');

$sql = "select name, id from categories where user_id=:user_id and id=:category_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$statement->execute();
$categories = $statement->fetch();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>カテゴリ編集</title>
</head>

<body>
<form action="./update.php" method="post">
<input type="hidden" name="id" value="<?php echo $categories['id']; ?>">
<table>
    <tr>
        <td><input type="text" name="category_name" value="<?php echo $categories['name']; ?>"></td>
        <td><button type="submit" name="button">更新</button></td>
    </tr>
</table>
</form>
<a href="./index.php">戻る</a><br>
</body>
</html>
