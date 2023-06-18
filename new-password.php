<?php
require __DIR__ . '/config/database.php';

// new-password.phpのURLにトークンの値を持っている場合
if (isset($_GET['token'])){
    $token = filter_var($_GET['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // DBから一致するトークンがあるか確認
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT token FROM users WHERE token=? AND is_deleted=0');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($dbToken);
    $stmt->fetch();

    // URLが1時間以内に発行されたものか確認
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT TIMESTAMPDIFF(MINUTE, updated_at, CURRENT_TIMESTAMP()) AS time FROM users WHERE token=? AND is_deleted=0 LIMIT 1');
    $stmt->bind_param('s', $dbToken);
    $stmt->execute();
    $stmt->bind_result($time);
    $stmt->fetch();

    // DBにトークンが存在しない場合、または
    // トークン発行（メール送信）から1時間経過した場合
    if ($dbToken === NULL || $time > 60){
        $_SESSION['new_password_error'] = '無効のURLです';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

// new-password.phpのURLにトークンの値を持っていない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

<!--================================ HTML ================================-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsukuba University News</title>

    <!-- CSS LINK -->
    <link rel="stylesheet" href="<?php echo ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- NOTO SANS JAPANESE GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <section class="form__section">
        <div class="container form__section-container">
            <h2>パスワード変更</h2>
            <!-- パスワード変更に失敗した場合 -->
            <?php if (isset($_SESSION['new_password_error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php                         
                        //インデックスを変数$iで指定
                        for($i = 0; $i < count($_SESSION['new_password_error']); $i++){
                            // 全エラーを表示
                            echo $_SESSION['new_password_error'][$i];
                            echo "<br>";
                        }
                        unset($_SESSION['new_password_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- 新しいパスワードを登録するフォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>new-password-logic.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $dbToken ?>">
                <input type="password" name="createdpassword" value="" placeholder="新しいパスワード">
                <input type="password" name="confirmedpassword" value="" placeholder="新しいパスワード（再入力）">
                <button type="submit" name="submit" class="btn purple">変更</button>
            </form>        
        </div>
    </section>

    <!--================ END OF NEW-PASSWORD ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>