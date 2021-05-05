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
    <title>スタッフ編集</title>
</head>
<body>
    <h3>スタッフ編集</h3>
    <?php if (empty($error_message)) :?>
        <form method="POST" action="edit_check.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= h($member['id']) ?>" >
            名前を入力してください。<br>
            <input type="text" name="name" value="<?= h($member['name']) ?>" style="width:200px"><br>
            年齢を入力してください。 <br>
            <input type="text" name="age" value="<?= h($member['age']) ?>" style="width:100px"><br>
            仕事を入力してください。 <br>
            <input type="text" name="job" value="<?= h($member['job']) ?>" style="width:100px"><br>
            ファイル:<br><input type="file" name="up_file"><br>
            現在のイメージ：<br>
            <img src="../images/<?= h($member['image_name']) ?>" alt="">
            <input type="hidden" name="image_name" value="<?= h($member['image_name']) ?>"><br>
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="送信">
        </form>
　　<?php else: ?>
        <div style="color:tomato"><?= h($error_message) ?></div>
        <input type="button" onclick="history.back()" value="戻る">
　　<?php endif; ?>
    
</body>
</html>