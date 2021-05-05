<?php
    require_once('../function/db.php');
    require_once('../function/function.php');

    $name = isset($_POST["name"]) ? shape($_POST["name"]) : "";
    $age = isset($_POST["age"]) ? shape($_POST["age"]) : "";
    $job = isset($_POST["job"]) ? shape($_POST["job"]) : "";
    $image_name = isset($_POST["image_name"]) ? shape($_POST["image_name"]) : "";

    try {
        // 画像がある場合とない場合
        if($image_name) {
            // echo "画像あり";
            $created_at = date("Y/m/d H:i:s");
            $updated_at = date("Y/m/d H:i:s");

            $dbh = db();
            $sql = "INSERT INTO users (name, age, job, image_name, created_at, updated_at) VALUES (:name, :age, :job, :image_name, :created_at, :updated_at)";
            $stmt = $dbh->prepare($sql);

            //クエリの設定
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':job', $job);
            $stmt->bindValue(':image_name', $image_name);
            $stmt->bindValue(':created_at', $created_at);
            $stmt->bindValue(':updated_at', $updated_at);

            //クエリの実行
            $stmt->execute();

            $dbh = null;

            // ファイル保存
            if(rename("../images/tmp/".$image_name, "../images/".$image_name)){
                // ファイルのパーミッションを確実に0644に設定する
                chmod("../images/" . $image_name, 0644);
                //正常
                echo "uploaded";
            }else{
                //コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
                echo "error while saving.";
            }

            $message = $name . "さんを追加しました。<br>";
        } else {
            // echo "画像なし";
            $created_at = date("Y/m/d H:i:s");
            $updated_at = date("Y/m/d H:i:s");

            $dbh = db();
            $sql = "INSERT INTO users (name, age, job, created_at, updated_at) VALUES (:name, :age, :job, :created_at, :updated_at)";
            $stmt = $dbh->prepare($sql);

            //クエリの設定
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':job', $job);
            $stmt->bindValue(':created_at', $created_at);
            $stmt->bindValue(':updated_at', $updated_at);

            //クエリの実行
            $stmt->execute();

            $dbh = null;

            $message = $name . "さんを追加しました。<br>";
        }

    } catch (PDOException $e) {
        // 本番ではヒントになるエラー文は表示しない
        $error_message =  "障害発生によりご迷惑をおかけしています。: " . $e->getMessage() . "\n";
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>staff_done</title>
</head>
<body>
    <?php if (!isset($error_message)) :?>
        <h2>スタッフ追加完了!</h2>
        <div><?= h($message) ?></div>
        <img src="../images/tmp/<?= h($image_name) ?>" alt="">
        <a href="../index.php"><button>戻る</button></a>
    <?php else: ?>
        <p style="color:tomato"><?= h($error_message) ?></p>
        <a href="../index.php"><button>戻る</button></a>
　　<?php endif; ?>

</body>
</html>