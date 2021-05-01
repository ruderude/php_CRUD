<?php
// エラーを出力する
ini_set('display_errors', "On");
require_once('../function/db.php');
require_once('../function/function.php');


if(isset($_GET['id']) && !empty($_GET['id'])) {
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
    <form method="POST" action="edit_check.php">
        <input type="hidden" name="id" value="<?= $member['id'] ?>" >
        名前を入力してください。<br>
        <input type="text" name="name" value="<?= $member['name'] ?>" style="width:200px"><br>
        年齢を入力してください。 <br>
        <input type="text" name="age" value="<?= $member['age'] ?>" style="width:100px"><br>
        仕事を入力してください。 <br>
        <input type="text" name="job" value="<?= $member['job'] ?>" style="width:100px"><br>
        <br>
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="送信">
    </form>
</body>
</html>