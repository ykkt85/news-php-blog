<?php
require 'config/database.php';

// 前回エラーの場合セッション値を表示
$email = $_SESSION['signup_data']['email'] ?? null;
$createdPassword = $_SESSION['signup_data']['createdpassword'] ?? null;
$confirmedPassword = $_SESSION['signup_data']['confirmedpassword'] ?? null;

// セッション値を破棄
unset($_SESSION['signup_data']);
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
            <h2>新しいユーザーを登録</h2>
            <!-- ユーザーの新規登録に失敗した場合 -->
            <?php if (isset($_SESSION['signup_error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['signup_error'];
                        unset($_SESSION['signup_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- ユーザー登録フォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>signup-logic.php" method="POST">
                <!-- 少人数ログイン想定・アドレス被り無し想定のためメールとパスワードのみ -->
                <input type="email" name="email" value="<?php echo h($email) ?>" placeholder="メールアドレス">
                <input type="password" name="createdpassword" value="<?php echo h($createdPassword) ?>" placeholder="パスワード">
                <input type="password" name="confirmedpassword" value="<?php echo h($confirmedPassword) ?>" placeholder="パスワード（確認）">
                <button type="submit" name="submit" class="btn purple">登録</button>
                <small>アカウントをお持ちの方は <b><a href="<?php echo ROOT_URL ?>login.php">こちら</a></b></small>    
            </form>
        </div>
    </section>
    <!--================ END OF SIGNUP ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>