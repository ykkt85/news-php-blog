<?php
include 'partials/header.php';
?>

<!--================================ HTML ================================-->

    <section class=" section__extra-margin">
        <!-- パスワード変更時のURLにおいてDBに登録されていないトークンが含まれている場合 -->
        <?php if (isset($_SESSION['new_password_error'])): ?>
            <div class ="alert__message error lg">
                <p><?php echo $_SESSION['new_password_error'];
                unset($_SESSION['new_password_error']) ?></p>
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

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
