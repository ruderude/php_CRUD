<?php
// エラーを出力する
ini_set('display_errors', "On");
require_once('function/db.php');

try {

    $dbh = db();
    
    $sql = "SELECT id, name FROM users";

    $stmt = $dbh->query($sql);

    $members = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $dbh = null;

} catch (PDOException $e) {
    // 本番ではヒントになるエラー文は表示しない
    $error_message =  "障害発生によりご迷惑をおかけしています。: " . $e->getMessage() . "\n";
    echo $error_message;
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
</head>
<body>
    <h3>トップページ</h3>
    <a href="./add/add.php">新規登録</a>
    <ul>
        <?php foreach ($members as $key => $member) : ?>
            <li style="display:flex;">
                <!-- エスケープしなくて良い？ -->
                <?= $member['name'] ?>
                <a href="./show/show.php?id=<?= $member['id'] ?>"><button>詳細</button></a>
                <a href="./edit/edit.php?id=<?= $member['id'] ?>"><button>編集</button></a>
                <a href="./delete/delete.php?id=<?= $member['id'] ?>"><button>削除</button></a>
            </li><br>
        <?php endforeach; ?>
    </ul>
</body>
</html>