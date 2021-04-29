<?php
// エラーを出力する
ini_set('display_errors', "On");

// require_once('../function/database.php');

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