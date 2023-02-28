<?php
include __DIR__ . '/partials/header.php';

// DBから記事の値を取得
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $connection = dbconnect();
    $postStmt = $connection->prepare('SELECT post_ID, title, thumbnail, body, user_ID FROM posts WHERE post_ID=? AND is_deleted=0');
    $postStmt->bind_param('i', $postID);
    $postStmt->execute();
    $postStmt->bind_result($postID, $title, $thumbnail, $body, $userID);
    $postStmt->fetch();

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
                        <?php echo $_SESSION['edit_post_error'];
                        unset($_SESSION['edit_post_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- 記事編集フォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="post_ID" value="<?php echo $postID ?>">    
                <input type="hidden" name="previous_thumbnail_name" value="<?php echo $thumbnail ?>">
                <input type="text" name="title" value="<?php echo h($title) ?>" placeholder="タイトル">
                <select name="tag_ID">
                    <?php
                    // タグを全種取得
                    $connection = dbconnect();
                    $tagStmt = $connection->prepare('SELECT tag_ID, tag_title FROM tags WHERE is_deleted=0');
                    $tagStmt->execute();
                    $tagStmt->bind_result($tagID, $tagTitle);
                    while ($tagStmt->fetch()): ?>
                        <option value="<?php echo $tagID ?>"><?php echo $tagTitle ?></option>     
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
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-POST ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>