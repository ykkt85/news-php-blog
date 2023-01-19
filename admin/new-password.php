<?php
require 'partials/header.php';
// 途中
?>

    <!--================ END OF NAV ================-->
    <section class="form__section">
        <div class="container form__section-container">
            <h2>パスワード変更</h2>
            <?php if (isset($_SESSION['new-password-success'])): ?>
                <div class="alert__message success">
                    <p>
                        <?php echo $_SESSION['new-password-success'];
                        unset($_SESSION['new-password-success']); ?>
                    </p>
                </div>
            <?php elseif (isset($_SESSION['new-password-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['new-password-error'];
                        unset($_SESSION['new-password-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/new-password-logic.php" method="POST">
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