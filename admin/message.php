<?php
include 'partials/header.php';

?>
    <!-- メールが送られたとき -->
    <!-- 途中 -->
    <?php if (isset($_SESSION['change-password-success'])): ?>
        <div class ="alert__message success lg">
            <p><?php echo $_SESSION['change-password-success'];
            unset($_SESSION['change-password-success']); ?></p>
        </div>
    <?php endif; ?>