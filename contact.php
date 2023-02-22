<?php
include __DIR__ . '/partials/header.php';

// エラー時にセッションデータを戻す
$title = $_SESSION['contact_data']['title'] ?? NULL;
$name = $_SESSION['contact_data']['name'] ?? NULL;
$email = $_SESSION['contact_data']['email'] ?? NULL;
$body = $_SESSION['contact_data']['body'] ?? NULL;

// セッションデータを消去
unset($_SESSION['contact_data']);
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>問い合わせ</h2>
            <!-- 問い合わせに成功した場合 -->
            <?php if (isset($_SESSION['contact_success'])):?>    
                <div class="alert__message success">
                    <p>
                        <?php echo $_SESSION['contact_success'];
                        unset($_SESSION['contact_success']); ?>
                    </p>
                </div>
            <!-- 問い合わせに失敗した場合 -->
            <?php elseif (isset($_SESSION['contact_error'])):?>    
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['contact_error'];
                        unset($_SESSION['contact_error']); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- 問い合わせフォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>contact-logic.php" enctype="multipart/form-data" method="POST">
                <input type="text" name="title" value="<?php echo h($title) ?>" placeholder="件名">
                <input type="text" name="name" value="<?php echo h($name) ?>" placeholder="名前">
                <input type="email" name="email" value="<?php echo h($email) ?>" placeholder="メールアドレス">
                <textarea rows="15" name="body" placeholder="本文"><?php echo h($body) ?></textarea>
                <button type="submit" name="submit" class="btn purple">送信</button>
            </form>
        </div>
    </section>
    <!--================ END OF CONARCT ================-->
    
<?php
include __DIR__ . '/partials/footer.php';
?>

</body>
</html>