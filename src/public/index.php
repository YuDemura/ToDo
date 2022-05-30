<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:./user/signin.php");
    exit();
}

$direction = filter_input(INPUT_GET, 'order');
if(empty($direction)) {
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

require_once __DIR__ . '/utils/pdo.php';

$pullDownMenuSql = <<<EOF
  SELECT
    id
    , name
  FROM
    categories
  WHERE
    user_id=:user_id
  ;
EOF;
$statement = $pdo->prepare($pullDownMenuSql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT tasks.contents, tasks.deadline, categories.name, tasks.status, tasks.id, tasks.category_id FROM tasks INNER JOIN categories ON tasks.category_id = categories.id ";
$sql = $sql . "WHERE tasks.user_id=:user_id ";
if (!empty($contents)) {
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
if (!empty($contents)) {
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
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<?php require_once __DIR__ . '/utils/header.php'; ?>

<body>
    <div class="container">
        <div class="bg-white rounded-lg shadow-md">
            <h2 class="text-gray-900 font-medium title-font">絞り込み検索</h2>
            <form action="" method="get">
                <div class="flex flex-wrap mb-2">
                    <div class="">
                        <div class="relative">
                            <input name="search" type="text" value="<?php echo $_GET['search'] ??
                            ''; ?>" placeholder="キーワードを入力" class=" bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                        </div>
                    </div>

                    <div class="">
                        <div class="relative">
                            <div>
                                <input type="radio" name="order" value="desc">
                                <span>新着順</span>
                            </div>
                            <div>
                                <input type="radio" name="order" value="asc">
                                <span>古い順</span>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="relative">
                            <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">カテゴリ</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="relative">
                            <div>
                                <input type="radio" name="status" value="1">
                                <span>完了</span>
                            </div>
                            <div>
                                <input type="radio" name="status" value="0">
                                <span>未完了</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="text-white bg-indigo-500 border-0 py-1 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Button</button>
            </form>
        </div>
    </div>

    <div class="container">
        <a href="./task/create.php">タスクを追加</a><br>
    </div>

    <div class="container px-5 mx-auto">
        <form action="?" method="post">
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2">タスク名</th>
                <th class="px-4 py-2">締め切り</th>
                <th class="px-4 py-2">カテゴリー名</th>
                <th class="px-4 py-2">完了未完了</th>
                <th class="px-4 py-2">編集</th>
                <th class="px-4 py-2">削除</th>
            </tr>
            </thead>

            <?php foreach ($tasks as $task): ?>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $task['contents']; ?></td>
                        <td class="border px-4 py-2"><?php echo $task['deadline']; ?></td>
                        <td class="border px-4 py-2"><?php echo $task['name']; ?></td>
                        <td class="border px-4 py-2">
                        <?php if($task['status'] == "0") : ?>
                        <td class="border px-4 py-2"><button class="text-white bg-green-300 border-0 py-1 px-1 focus:outline-none hover:bg-green-400 rounded text-lg" type="submit" name="status" value="1" formaction="./task/updateStatus.php?id=<?php echo $task['id']; ?>">未完了</button></td>
                        <?php elseif ($task['status'] == "1") : ?>
                        <td class="border px-4 py-2"><button class="text-white bg-green-300 border-0 py-1 px-1 focus:outline-none hover:bg-green-400 rounded text-lg" type="submit" name="status" value="0" formaction="./task/updateStatus.php?id=<?php echo $task['id']; ?>">完了</button></td>
                        <?php endif; ?>
                        <td class="border px-4 py-2"><a href="./task/edit.php?id=<?php echo $task['id']; ?>">編集</a></td>
                        <td class="border px-4 py-2"><button class="text-white bg-red-300 border-0 py-1 px-1 focus:outline-none hover:bg-red-400 rounded text-lg" type="submit" name="delete" formaction="./task/delete.php?id=<?php echo $task['id']; ?>">削除</button></td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
        </table>
        </form>
    </div>
</body>
</html>
