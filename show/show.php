<?php
require_once('../function/db.php');
require_once('../function/function.php');

$id = isset($_GET["id"]) ? shape($_GET["id"]) : "";
$error_message;

if($id) {

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
    }
} else {
    $url = '../index.php';
    header('Location: ' . $url, true, 301);
    exit;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細ページ</title>
</head>
<body>
    <h3>詳細ページ</h3>
    <?php if (empty($error_message)) :?>
        <ul>
            <li>
            ID：<?= h($member['id']) ?>
            </li>
            <li>
            名前：<?= h($member['name']) ?>
            </li>
            <li>
            年齢：<?= h($member['age']) ?>
            </li>
            <li>
            仕事：<?= h($member['job']) ?>
            </li>
        </ul>
        <form method="POST" action="delete_done.php">
            <input type="hidden" name="id" value="<?= h($id) ?>">
            <input type="hidden" name="name" value="<?= h($member['name']) ?>">
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="削除する">
        </form>
　　<?php else: ?>
        <div style="color:tomato"><?= h($error_message) ?></div>
        <input type="button" onclick="history.back()" value="戻る">
　　<?php endif; ?>
</body>
</html>