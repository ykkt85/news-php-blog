<?php
include 'partials/header.php';

if (isset($_GET['user_ID'])){
    $user_ID = filter_var($_GET['user_ID'], FILTER_SANITIZE_NUMBER_INT);

    // DBからデータを取得
    $query = "SELECT * FROM users WHERE user_id=$user_ID";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}
?>

    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>投稿者・管理者編集</h2>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-user-logic.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" value="<?php echo $user['user_ID'] ?>" name="user_ID">
                <input type="email" value="<?php echo $user['email'] ?>" name="email" readonly>
                <select name="role_ID">
                    <option value="0">投稿者</option>
                    <option value="1">管理者</option>
                </select>
                <button type="submit" name="submit" class="btn purple">保存</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-USER ================-->
    
    <script src="../js/main.js"></script>
</body>
</html>