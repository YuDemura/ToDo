<?php
session_start();

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

$user_id = $_SESSION['user_id'];
$task_id = filter_input(INPUT_GET, 'id');

$sql = "SELECT categories.name, tasks.contents, tasks.deadline, tasks.id, categories.id FROM tasks INNER JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = :user_id and tasks.id = :task_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$statement->execute();
$tasks = $statement->fetch();

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
  <title>タスク編集</title>
</head>

<body>
<form action="./update.php" method="post">
<input type="hidden" name="id" value="<?php echo $tasks['3']; ?>">
<table>
    <tr>
        <td><select name="category_id">
                <option value="<?php echo $tasks['id']; ?>" ><?php echo $tasks['name']; ?></option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="text" name="contents" value="<?php echo $tasks['contents']; ?>"></td>
        <td><input type="date" name="deadline" value="<?php echo $tasks['deadline']; ?>"></td>
        <td><button type="submit" name="button">更新</button></td>
    </tr>
</table>
</form>
<div>
<a href="../index.php">戻る</a><br>
</div>
</body>
</html>
