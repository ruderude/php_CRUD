<?php
    require_once('../function/db.php');
    require_once('../function/function.php');

    $id = isset($_POST["id"]) ? shape($_POST["id"]) : "";
    $name = isset($_POST["name"]) ? shape($_POST["name"]) : "";
    $age = isset($_POST["age"]) ? shape($_POST["age"]) : "";
    $job = isset($_POST["job"]) ? shape($_POST["job"]) : "";

    $error_messages = [];

    if($name == "") {
        $error_messages[] = "名前を入力してください";
    } elseif(mb_strlen($name) > 30) {
        $error_messages[] = "名前が長すぎます（30文字以下にしてください）";
    }

    if($age == "") {
        $error_messages[] = "年齢を入力してください";
    }elseif (!preg_match("/^[0-9]+$/",$age)) {
        $error_messages[] = "数値を入力してください";
    } elseif ($age > 200) {
        $error_messages[] = "生きていません";
    }

    if($job == "") {
        $error_messages[] = "仕事名を入力してください";
    } elseif(mb_strlen($job) > 30) {
        $error_messages[] = "仕事名が長すぎます（30文字以下にしてください）";
    }

    ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ編集チェック</title>
</head>
<body>
<h2>スタッフ編集チェック</h2>
    <?php if (empty($error_messages)) :?>
        <h4>こちらの内容でよろしいですか？</h4>
        <form method="post" action="edit_done.php" enctype="multipart/form-data">
            <ul>
                <li><?= h($name) ?></li>
                <li><?= h($age) ?>歳</li>
                <li><?= h($job) ?></li>
            </ul>
            <input type="hidden" name="id" value="<?= h($id) ?>">
            <input type="hidden" name="name" value="<?= h($name) ?>">
            <input type="hidden" name="age" value="<?= h($age) ?>">
            <input type="hidden" name="job" value="<?= h($job) ?>">
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
　　<?php else: ?>
        <?php foreach ($error_messages as $error) :?>
            <div style="color:tomato"><?= h($error) ?></div>
        <?php endforeach; ?>
        <input type="button" onclick="history.back()" value="戻る">
　　<?php endif; ?>
</body>
</html>