<?php
include __DIR__ . '/partials/header.php';

// edit-user.phpのURLにuser_IDの値が含まれている場合
if (isset($_GET['user_ID'])){
    $userID = filter_var($_GET['user_ID'], FILTER_SANITIZE_NUMBER_INT);

    // 管理者以外（投稿者）が編集しようとした場合、または
    // ログインユーザーが本人のedit-user.phpを編集しようとした場合
    if ($_SESSION['role_ID'] === 0 || $_SESSION['user_ID'] === $userID){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

    // DBから値を取得
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT user_ID, email FROM users WHERE user_id=?');
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $stmt->bind_result($userID, $email);
    $stmt->fetch();

// edit-user.phpのURLにuser_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>投稿者・管理者編集</h2>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-user-logic.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" value="<?php echo $userID ?>" name="user_ID">
                <input type="email" value="<?php echo h($email) ?>" name="email" readonly>
                <select name="role_ID">
                    <option value="0">投稿者</option>
                    <option value="1">管理者</option>
                </select>
                <button type="submit" name="submit" class="btn purple">保存</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-USER ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>