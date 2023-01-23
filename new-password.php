<?php
require 'config/database.php';

// URLにトークンの値を持っているか確認
if (isset($_GET['token'])){
    $token = filter_var($_GET['token'], FILTER_SANITIZE_NUMBER_INT);

    // DBから一致するトークンを持つ投稿者を取得
    $query = "SELECT * FROM users WHERE token=$token AND is_deleted=0";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
    }
} else {
    header('location: ' . ROOT_URL);
    die();
}
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
            <!-- パスワード変更に失敗した場合 -->
            <?php if (isset($_SESSION['new-password-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['new-password-error'];
                        unset($_SESSION['new-password-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>new-password-logic.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $user['token'] ?>">
                <input type="password" name="createdpassword" value="" placeholder="新しいパスワード">
                <input type="password" name="confirmedpassword" value="" placeholder="新しいパスワード（再入力）">
                <button type="submit" name="submit" class="btn purple">変更</button>
            </form>        
        </div>
    </section>

    <!--================ END OF LOGIN ================-->

    <script src="../js/main.js"></script>
</body>
</html>