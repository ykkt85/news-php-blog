<?php
include __DIR__ . '/partials/header.php';

// 前回エラー時にセッション値を表示
$title = $_SESSION['edit_post_data']['title'] ?? NULL;
$body = $_SESSION['edit_post_data']['body'] ?? NULL;

// セッション値を消去
unset($_SESSION['edit_post_data']);

// DBから記事の値を取得
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $connection = dbconnect();
    $postStmt = $connection->prepare('SELECT post_ID, title, thumbnail, body, user_ID FROM posts WHERE post_ID=? AND is_deleted=0');
    $postStmt->bind_param('i', $postID);
    $postStmt->execute();
    $postStmt->bind_result($postID, $title, $thumbnail, $body, $userID);
    $postStmt->fetch();

    // CSRF対策のトークン発行
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;

    //ログイン中のユーザーと記事投稿ユーザーが異なる場合
    if ($_SESSION['user_ID'] !== $userID){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

} else {
    header('location:'. ROOT_URL .'admin/');
    die();
}
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>記事編集</h2>
            <!-- 記事編集に失敗した場合 -->
            <?php if (isset($_SESSION['edit_post_error'])):?>    
                <div class="alert__message error">
                    <p>
                        <?php
                        //インデックスを変数$iで指定
                        for($i = 0; $i < count($_SESSION['edit_post_error']); $i++){
                            // 全エラーを表示
                            echo $_SESSION['edit_post_error'][$i];
                            echo "<br>";
                        }                        
                        unset($_SESSION['edit_post_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- 記事編集フォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="post_ID" value="<?php echo $postID ?>">
                <input type="hidden" name="previous_thumbnail_name" value="<?php echo $thumbnail ?>">
                <input type="text" name="title" value="<?php echo h($title) ?>" placeholder="タイトル">
                <select name="category_ID">
                    <?php
                    // カテゴリを全種取得
                    $connection = dbconnect();
                    $categoryStmt = $connection->prepare('SELECT category_ID, category_title FROM categories WHERE is_deleted=0');
                    $categoryStmt->execute();
                    $categoryStmt->bind_result($categoryID, $categoryTitle);
                    while ($categoryStmt->fetch()): ?>
                        <option value="<?php echo $categoryID ?>"><?php echo $categoryTitle ?></option>     
                    <?php endwhile; ?>
                </select>
                <div class="form__control inline">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" checked>
                    <label for="is_featured">注目記事</label>
                </div>
                <div class="form__control">
                    <label for="thumbnail">写真を変更</label>
                    <input type="file" name="thumbnail" id="thumbnail">
                </div>
                <textarea rows="15" name="body" placeholder="本文"><?php echo h($body) ?></textarea>
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-POST ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>