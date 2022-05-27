<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$category_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT name, id FROM categories WHERE user_id=:user_id";
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
  <title>カテゴリ一覧</title>
</head>

<?php require_once __DIR__ . '/../utils/header.php'; ?>

<body>
    <h1>カテゴリ一覧</h1>
      <form action="?" method="post">
          <input name="category_name" type="text" placeholder="カテゴリー追加">
        <button type="submit" name="register" formaction="./store.php">登録</button>
      <table border="1">
        <?php foreach ($categories as $category): ?>
          <tr>
            <td><?php echo $category['name']; ?></td>
            <td><a href="./edit.php?id=<?php echo $category['id']; ?>">編集</a></td>
            <td><button type="submit" name="delete" formaction="./delete.php?id=<?php echo $category['id']; ?>">削除</button></td>
          </tr>
        <?php endforeach; ?>
      </table>
      </form>
        <a href="/task/create.php">戻る</a><br>
</body>

</html>
