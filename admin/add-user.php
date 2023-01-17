<?php
include 'partials/header.php';

// エラー時にセッションデータを戻す
$email = $_SESSION['add-user-data']['email'] ?? NULL;
$createdpassword = $_SESSION['add-user-data']['createdpassword'] ?? NULL;
$confirmedpassword = $_SESSION['add-user-data']['confirmedpassword'] ?? NULL;
$role_ID = $_SESSION['add-user-data']['role_ID'] ?? NULL;

// セッションデータを消去
unset($_SESSION['add-user-data']);
?>
    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>投稿者・管理者を追加</h2>
            <?php if (isset($_SESSION['add-user-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['add-user-error'];
                        unset($_SESSION['add-user-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-user-logic.php" enctype="multipart/form-data" method="POST">
                <input type="email" name="email" value="<?php echo $email ?>" placeholder="メールアドレス">
                <input type="password" name="createdpassword" value="<?php echo $createdpassword ?>" placeholder="パスワード">
                <input type="password" name="confirmedpassword" value="<?php echo $confirmedpassword ?>" placeholder="パスワード（確認）">
                <select name="role_ID">
                    <option value="0">投稿者</option>
                    <option value="1">管理者</option>
                </select>
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-USER ================-->
    
    <script src="../js/main.js"></script>
</body>
</html>