<?php
// エラーを出力する
ini_set('display_errors', "On");
require_once('../function/db.php');
require_once('../function/function.php');

$id = shape($_GET["id"]);
$id = h($id);


try {
    $dbh = db();
    
    $sql = "SELECT * FROM users WHERE id = :id";

    $stmt = $dbh->prepare($sql);
    
    //クエリの設定
    $stmt->bindValue(':id', $id);

    //クエリの実行
    $stmt->execute();

    $member = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>削除ページ</title>
</head>
<body>
    <h3>削除ページ</h3>
    <div>以下のスタッフを削除してよろしいですか？</div>
    <ul>
        <li>
        ID：<?= $member['id'] ?>
        </li>
        <li>
        名前：<?= $member['name'] ?>
        </li>
        <li>
        年齢：<?= $member['age'] ?>
        </li>
        <li>
        仕事：<?= $member['job'] ?>
        </li>
    </ul>
    <form method="POST" action="delete_done.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="name" value="<?= $member['name'] ?>">
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="削除する">
    </form>
    
</body>
</html>