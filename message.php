<?php
include __DIR__ . '/partials/header.php';
?>

<!--================================ HTML ================================-->

    <section class=" section__extra-margin">
        <!-- パスワード変更を完了できなかった場合 -->
        <?php if (isset($_SESSION['new_password_error'])): ?>
            <div class ="alert__message error lg">
                <p><?php echo $_SESSION['new_password_error'];
                unset($_SESSION['new_password_error']) ?></p>
            </div>
        <!-- パスワード変更を完了できた場合 -->
        <?php elseif (isset($_SESSION['new_password_success'])): ?>
            <div class ="alert__message success lg">
                <p><?php echo $_SESSION['new_password_success'];
                unset($_SESSION['new_password_success']) ?></p>
            </div>
        <!-- パスワード変更後メールが送信された場合 -->
        <?php elseif (isset($_SESSION['reset_password_success'])): ?>
            <div class ="alert__message success lg">
                <p><?php echo $_SESSION['reset_password_success'];
                unset($_SESSION['reset_password_success']) ?></p>
            </div>
        <!-- 問い合わせフォームから問い合わせを送った場合 -->
        <?php elseif (isset($_SESSION['contact_success'])): ?>
            <div class ="alert__message success lg">
                <p><?php echo $_SESSION['contact_success'];
                unset($_SESSION['contact_success']) ?></p>
            </div>
        <!-- セッション値のメッセージがない場合 -->
        <?php else:
            header('location: '. ROOT_URL);
        endif; ?>
    </section>
    <!--================ END OF INFO ================-->

<?php
include __DIR__ . '/partials/footer.php';
?>