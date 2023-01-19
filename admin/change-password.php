<?php
require 'partials/header.php';

// 現在のユーザー情報を取得
$current_user_id = $_SESSION['user_ID'];
$query = "SELECT email FROM users WHERE user_ID=$current_user_id AND is_deleted=0";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);
?>

    <!--================ END OF NAV ================-->
    <section class="form__section">
        <div class="container form__section-container">
            <h2>パスワード変更</h2>
            <?php if (isset($_SESSION['change-password-success'])): ?>
                <div class="alert__message success">
                    <p>
                        <?php echo $_SESSION['change-password-success'];
                        unset($_SESSION['change-password-success']); ?>
                    </p>
                </div>
            <?php elseif (isset($_SESSION['change-password-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['change-password-error'];
                        unset($_SESSION['change-password-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/change-password-logic.php" method="POST">
                <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="メールアドレス" readonly>
                <input type="password" name="previouspassword" value="" placeholder="現在のパスワード">
                <button type="submit" name="submit" class="btn purple">変更</button>
            </form>        
        </div>
    </section>

    <!--================ END OF LOGIN ================-->

    <script src="../js/main.js"></script>
</body>
</html>