<?php
    require_once('../function/db.php');
    require_once('../function/function.php');

    $id = isset($_POST["id"]) ? shape($_POST["id"]) : "";
    $name = isset($_POST["name"]) ? shape($_POST["name"]) : "";
    $age = isset($_POST["age"]) ? shape($_POST["age"]) : "";
    $job = isset($_POST["job"]) ? shape($_POST["job"]) : "";
    $image_name = isset($_POST["image_name"]) ? shape($_POST["image_name"]) : null;
    $old_image_name = isset($_POST["old_image_name"]) ? shape($_POST["old_image_name"]) : null;

    // var_dump($old_image_name);

    if($image_name) {
        try {

            $updated_at = date("Y/m/d H:i:s");
    
            $dbh = db();
    
            $sql = "UPDATE users SET name = :name, age = :age, job = :job, image_name = :image_name, updated_at = :updated_at WHERE id = :id";
    
            $stmt = $dbh->prepare($sql);
    
            //クエリの設定
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':age', $age);
            $stmt->bindValue(':job', $job);
            $stmt->bindValue(':image_name', $image_name);
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

            // 古いファイルが存在すれば削除
            if($old_image_name){
                if(file_exists("../images/".$old_image_name)){
                    unlink("../images/".$old_image_name);
                }
            }
    
            $message = $name . "さんを更新しました。<br>";
    
        } catch (PDOException $e) {
            // 本番ではヒントになるエラー文は表示しない
            $error_message =  "障害発生によりご迷惑をおかけしています。: " . $e->getMessage() . "\n";
        }

    } else {
        try {

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
        }

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