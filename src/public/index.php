<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:./user/signin.php");
    exit();
}

include ('header.php');

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=todo; charset=utf8', $dbUserName, $dbPassword);

if (isset($_GET['order'])) {
    $direction = $_GET['order'];
} else {
    $direction = 'desc';
}

if (isset($_GET['search'])) {
    $contents = '%' . $_GET['search'] . '%';
} else {
    $contents = '%%';
}

$category_id = filter_input(INPUT_GET, 'category_id');
$status = filter_input(INPUT_GET, 'status');
$user_id = $_SESSION['user_id'];

$sql = "SELECT tasks.contents, tasks.deadline, categories.name, tasks.status, tasks.id, tasks.category_id FROM tasks INNER JOIN categories ON tasks.category_id = categories.id ";
$sql = $sql . "WHERE tasks.user_id=:user_id ";
if (isset($contents)) {
    $sql = $sql . " and tasks.contents LIKE :contents";
}
if (!empty($category_id)) {
    $sql = $sql . " and categories.id=:category_id ";
}
if (isset($status)) {
    $sql = $sql . " and tasks.status=:status";
}
$sql = $sql . " ORDER BY id $direction";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
if (isset($contents)) {
    $stmt->bindValue(':contents', $contents, PDO::PARAM_STR);
}
if (!empty($category_id)) {
    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
}
if (isset($status)) {
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
}
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ホーム画面</title>
</head>

<body>
    <p>絞り込み検索</p>
    <form action="" method="get">
    <table>
        <tr>
            <td><input name="search" type="text" value="<?php echo $_GET['search'] ??
              ''; ?>" placeholder="キーワードを入力"></td>

            <td><p><input type="radio" name="order" value="desc">
            新着順</p>
            <p><input type="radio" name="order" value="asc">
            古い順</p></td>

            <td><select name="category_id">
                    <option value="">カテゴリ</option>
                    <?php foreach ($tasks as $task): ?>
                    <option value="<?php echo $task['category_id']; ?>"><?php echo $task['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td><p><input type="radio" name="status" value="1">
            完了</p>
            <p><input type="radio" name="status" value="0">
            未完了</p></td>

        </tr>
            <button type="submit">Button</button>
    </table>
    </form>
        <a href="./task/create.php">タスクを追加</a><br>
        <form action="?" method="post">
        <table border="1">
            <tr>
                <th>タスク名</th>
                <th>締め切り</th>
                <th>カテゴリー名</th>
                <th>完了未完了</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?php echo $task['contents']; ?></td>
            <td><?php echo $task['deadline']; ?></td>
            <td><?php echo $task['name']; ?></td>
            <td>
                <?php if($task['status'] == "0") : ?>
                    <button type="submit" name="status" value="1" formaction="./task/updateStatus.php?id=<?php echo $task['id']; ?>">未完了</button>
                <?php elseif ($task['status'] == "1") : ?>
                    <button type="submit" name="status" value="0" formaction="./task/updateStatus.php?id=<?php echo $task['id']; ?>">完了</button>
                <?php endif; ?>
            </td>
            <td><a href="./task/edit.php?id=<?php echo $task['id']; ?>">編集</a></td>
            <td><button type="submit" name="delete" formaction="./task/delete.php?id=<?php echo $task['id']; ?>">削除</button></td>
        </tr>
        <?php endforeach; ?>
        </table>
        </form>
</body>
</html>
