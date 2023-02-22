<?php
require __DIR__ . '/config/database.php';

// new-password.phpのURLにトークンの値を持っている場合
if (isset($_GET['token'])){
    $token = filter_var($_GET['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // DBから一致するトークンを持つ投稿者を取得
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT email, token FROM users WHERE token=? AND is_deleted=1 LIMIT 1');
    $stmt->bind_param('s', $token);
    $success = $stmt->execute();
    $stmt->bind_result($email, $dbToken);
    $stmt->fetch();

    // DBにトークンが存在しない場合
    if (!isset($dbToken)){
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
    <!-- MONTSERRAT GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <section class="form__section">
        <div class="container form__section-container">
            <h2>パスワード変更されたユーザー</h2>
            <p>「確認」を押してパスワード変更を完了してください</p>
            <!-- 新しいパスワードを登録するフォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>new-password-logic.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $dbToken ?>">
                <input type="email" name="email" value="<?php echo $email ?>" readonly>
                <button type="submit" name="submit" class="btn purple">確認</button>
            </form>        
        </div>
    </section>

    <!--================ END OF NEW-PASSWORD ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>