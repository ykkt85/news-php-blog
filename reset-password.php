<?php
require 'config/database.php';

// 前回エラー時のセッション値を表示
$email = $_SESSION['reset-password-data']['email'] ?? NULL;

// セッション値を破棄
unset($_SESSION['reset-password-data']);
?>

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
            <h2>パスワード変更</h2>
            <!-- 一致するメールアドレスが存在する時 -->
            <?php if (isset($_SESSION['reset-password-success'])): ?>
                <div class="alert__message success">
                    <p>
                        <?php echo $_SESSION['reset-password-success'];
                        unset($_SESSION['reset-password-success']); ?>
                    </p>
                </div>
            <!-- 一致するメールアドレスが存在しない時 -->
            <?php elseif (isset($_SESSION['reset-password-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['reset-password-error'];
                        unset($_SESSION['reset-password-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- パスワードリセット用のフォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>reset-password-logic.php" method="POST">
                <input type="email" name="email" value="<?php echo h($email) ?>" placeholder="メールアドレス">
                <button type="submit" name="submit" class="btn purple">確認</button>
            </form>        
        </div>
    </section>
    <!--================ END OF RESET-PASSWORD ================-->

    <script src="js/main.js"></script>
</body>
</html>