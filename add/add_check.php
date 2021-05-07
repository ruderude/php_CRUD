<?php
    require_once('../function/function.php');

    // var_dump($_POST);
    // exit;

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

    if(empty($error_messages)) {
        try {
            // throw new Exception('いきなりエラー！');
            $tmp_path = isset($_FILES['up_file']['tmp_name']) ? $_FILES['up_file']['tmp_name'] : null;
            if(is_uploaded_file($tmp_path)){
                // エラーコードをチェック
                if($_FILES['up_file']['error'] !== 0) {
                    throw new Exception('ファイルがアップロードできませんでした');
                }

                // サイズを調べる
                if ($_FILES['up_file']['size'] > 1024 * 1024 * 10) {
                    throw new Exception('ファイルサイズが大きすぎます');
                }

                // タイプを調べる
                $mime = shell_exec('file -bi '.escapeshellcmd($tmp_path));
                $mime = trim($mime);
                $mime = preg_replace("/ [^ ]*/", "", $mime);
                // print($mime);
                // exit;
                $extension = "";
                switch ($mime){
                    case 'image/gif;':
                        $extension = ".gif";
                        break;
                    case 'image/jpeg;':
                        $extension = ".jpeg";
                        break;
                    case 'image/jpg;':
                        $extension = ".jpg";
                        break;
                    case 'image/png;':
                        $extension = ".png";
                        break;
                    default:
                        throw new Exception('ファイル形式が不正です');
                        break;
                }

                // ファイル名はサーバー側で作る
                //独自のファイル名
                $originalFileName = 'image_' . date('YmdHis') . mt_rand() . $extension;
                var_dump($originalFileName);

                //一字ファイルを保存ファイルにコピーできたか
                if(move_uploaded_file($tmp_path,"../images/tmp/".$originalFileName)){

                    // ファイルのパーミッションを確実に0644に設定する
                    chmod("../images/tmp/" . $originalFileName, 0644);
        
                    //正常
                    echo "uploaded";
        
                }else{
                    //コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
                    $originalFileName = "";
                    // throw new Exception('ファイルがアップロードできませんでした');
                    echo "error while saving.";
                }
        
            }else{
                //そもそもファイルが来ていない。
                $originalFileName = "";
                // throw new Exception('ファイルがアップロードできませんでした');
                echo "no file.";
        
            }
        } catch (Exception $e) {
            $error_messages[] =  $e->getMessage();
        }
    }    

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録チェック</title>
</head>
<body>
<h2>新規登録チェック</h2>
    <?php if (empty($error_messages)) :?>
        <h4>こちらの内容でよろしいですか？</h4>
        <ul>
            <li><?= h($name) ?></li>
            <li><?= h($age) ?>歳</li>
            <li><?= h($job) ?></li>
        </ul>
        <img src="../images/tmp/<?= $originalFileName ?>" alt="">

        <form method="post" action="add_done.php" enctype="multipart/form-data">
            <input type="hidden" name="name" value="<?= h($name) ?>">
            <input type="hidden" name="age" value="<?= h($age) ?>">
            <input type="hidden" name="job" value="<?= h($job) ?>">
            <input type="hidden" name="image_name" value="<?= h($originalFileName) ?>">
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
　　<?php else: ?>
        <?php foreach ($error_messages as $error) : ?>
            <div style="color:tomato"><?= $error ?></div>
        <?php endforeach; ?>
        <input type="button" onclick="history.back()" value="戻る">
　　<?php endif; ?>
</body>
</html>