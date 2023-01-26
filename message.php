<?php
include 'partials/header.php';
?>
    <!-- パスワード変更時のURLにおいてDBに登録されていないトークンが含まれている場合 -->
    <section class=" section__extra-margin">
        <?php if (isset($_SESSION['new-password-error'])): ?>
            <div class ="alert__message error lg">
                <p><?php echo $_SESSION['new-password-error'];
                unset($_SESSION['new-password-error']) ?></p>
            </div>
        <?php endif; ?>
    </section>
    <!--================ END OF INFO ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
