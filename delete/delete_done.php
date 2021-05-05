<?php
    require_once('../function/db.php');
    require_once('../function/function.php');

    $id = isset($_POST["id"]) ? shape($_POST["id"]) : "";
    $name = isset($_POST["name"]) ? shape($_POST["name"]) : "";
    $image_name = isset($_POST["image_name"]) ? shape($_POST["image_name"]) : "";
    $error_message;

    try {

        $dbh = db();
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $dbh->prepare($sql);

        //クエリの設定
        $stmt->bindValue(':id', $id);

        //クエリの実行
        $stmt->execute();

        $dbh = null;

        // ファイルが存在すれば削除
        if($image_name){
            if(file_exists("../images/".$image_name)){
                unlink("../images/".$image_name);
            }
        }
        

        $message = $name . "さんを削除しました。<br>";

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
    <title>スタッフ削除完了</title>
</head>
<body>
    <?php if (!isset($error_message)) :?>
        <h2>スタッフ削除完了!</h2>
        <div><?= h($message) ?></div>
        <a href="../index.php"><button>戻る</button></a>
    <?php else: ?>
        <p style="color:tomato"><?= $error_message ?></p>
        <a href="../index.php"><button>戻る</button></a>
　　<?php endif; ?>

</body>
</html>