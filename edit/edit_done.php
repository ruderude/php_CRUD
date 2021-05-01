<?php
    require_once('../function/db.php');
    require_once('../function/function.php');

    try {
        $id = shape($_POST["id"]);
        $name = shape($_POST["name"]);
        $age = shape($_POST["age"]);
        $job = shape($_POST["job"]);
        
        $id = h($id);
        $name = h($name);
        $age = h($age);
        $job = h($job);

        $updated_at = date("Y/m/d H:i:s");

        $dbh = db();

        $sql = "UPDATE users SET name = :name, age = :age, job = :job, updated_at = :updated_at WHERE id = :id";

        $stmt = $dbh->prepare($sql);

        //クエリの設定
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':age', $age);
        $stmt->bindValue(':job', $job);
        $stmt->bindValue(':updated_at', $updated_at);

        //クエリの実行
        $stmt->execute();

        $dbh = null;

        $message = $name . "さんを更新しました。<br>";

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
    <title>スタッフ更新完了</title>
</head>
<body>
    <?php if (!isset($error_message)) :?>
        <h2>スタッフ更新完了!</h2>
        <div><?= $message ?></div>
        <a href="../index.php"><button>戻る</button></a>
    <?php else: ?>
        <p style="color:tomato"><?= $error_message ?></p>
        <a href="../index.php"><button>戻る</button></a>
　　<?php endif; ?>

</body>
</html>