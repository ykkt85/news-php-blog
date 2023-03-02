<?php
include __DIR__ . '/partials/header.php';

// edit-category.phpのURLからcategory_IDの値を受け取った場合
if (isset($_GET['category_ID'])){
    $categoryID = filter_var($_GET['category_ID'], FILTER_SANITIZE_NUMBER_INT);

    // 管理者以外（投稿者）がアクセスした場合
    if ($_SESSION['role_ID'] === 0){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }
    
    // DBから値を取り出す
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT category_ID, category_title, description FROM categories WHERE category_ID=? AND is_deleted=0');
    $stmt->bind_param('i', $categoryID);
    $stmt->execute();
    $stmt->bind_result($categoryID, $categoryTitle, $description);
    $stmt->fetch();
    var_dump($categoryID, $categoryTitle, $description);

    // CSRF対策のトークン発行
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;

// URLからcategory_IDの値を受け取っていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-categorys.php');
    die();
}
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>カテゴリ編集</h2>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-category-logic.php" method="POST">
                <input type="hidden" name="category_ID" value="<?php echo $categoryID ?>">
                <input type="text" name="category_title" value="<?php echo h($categoryTitle) ?>" placeholder="タグ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo h($description) ?></textarea>
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <button type="submit" name="submit" class="btn purple">保存</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-category ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>