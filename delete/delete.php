<?php
// require_once('../function/database.php');
// エラーを出力する
ini_set('display_errors', "On");

$id = trim(mb_convert_kana($_GET["id"], "s", 'UTF-8'));
$id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');


try {
    $dsn = 'mysql:host=127.0.0.1;dbname=test_db;charset=utf8mb4';
    $user = 'admin';
    $password = 'password';

    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
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