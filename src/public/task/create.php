<?php
session_start();

include ('../header.php');

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$user_id = $_SESSION['user_id'];

$sql = 'SELECT * FROM categories WHERE user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>タスク新規作成</title>
</head>

<body>
<a href="/category/index.php">カテゴリを追加</a><br>
<form action="./store.php" method="post">
<table>
    <tr>
        <td><select name="category_id">
            <option value="">カテゴリを選んでください</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
            </select>
        </td>
        <td><input type="text" name="contents" placeholder="タスクを追加"></td>
        <td><input type="date" name="deadline"></td>
        <td><button type="submit" name="button">追加</button></td>
    </tr>
</table>
</form>
<a href="../index.php">戻る</a><br>
</body>
</html>
