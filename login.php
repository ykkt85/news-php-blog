<?php
require __DIR__ . '/config/database.php';

// 前回エラー時のセッション値を表示
$email = $_SESSION['login_data']['email'] ?? NULL;
$password = $_SESSION['login_data']['password'] ?? NULL;

// セッション値を破棄
unset($_SESSION['login_data']);
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
            <h2>ログイン</h2>
            <!-- 新規投稿者をを登録した時 -->
            <?php if (isset($_SESSION['signup_success'])): ?>
                <div class="alert__message success">
                    <p>
                        <?php echo $_SESSION['signup_success'];
                        unset($_SESSION['signup_success']); ?>
                    </p>
                </div>
            <!-- パスワードを変更したとき -->
            <?php if (isset($_SESSION['new_password_success'])): ?>
                <div class ="alert__message success">
                    <p><?php echo $_SESSION['new_password_success'];
                    unset($_SESSION['new_password_success']); ?></p>
                </div>
            <?php endif; ?>
            <!-- ログインに失敗した時 -->
            <?php elseif (isset($_SESSION['login_error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['login_error'];
                        unset($_SESSION['login_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- ログインフォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>login-logic.php" method="POST">
                <input type="email" name="email" value="<?php echo h($email) ?>" placeholder="メールアドレス">
                <input type="password" name="password" value="<?php echo h($password) ?>" placeholder="パスワード">
                <button type="submit" name="submit" class="btn purple">ログイン</button>
                <small>アカウントをお持ちでない場合は <b><a href="<?php echo ROOT_URL ?>signup.php">こちら</a></b></small>
                <small>パスワードを忘れた場合は <b><a href="<?php echo ROOT_URL ?>reset-password.php">こちら</a></b></small>
            </form>        
        </div>
    </section>
    <!--================ END OF LOGIN ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>