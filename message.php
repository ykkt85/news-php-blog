<?php
include 'partials/header.php';
?>
    <section class=" section__extra-margin">
        <!-- パスワード変更時のURLにおいてDBに登録されていないトークンが含まれている場合 -->
        <?php if (isset($_SESSION['new-password-error'])): ?>
            <div class ="alert__message error lg">
                <p><?php echo $_SESSION['new-password-error'];
                unset($_SESSION['new-password-error']) ?></p>
            </div>
        <!-- 問い合わせフォームから問い合わせを送った場合 -->
        <?php elseif (isset($_SESSION['contact-success'])): ?>
            <div class ="alert__message error lg">
                <p><?php echo $_SESSION['contact-success'];
                unset($_SESSION['contact-success']) ?></p>
            </div>
        <!-- セッション値のメッセージがない場合 -->
        <?php else:
            header('location: '. ROOT_URL);
        endif; ?>
    </section>
    <!--================ END OF INFO ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
