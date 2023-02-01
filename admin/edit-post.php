<?php
include 'partials/header.php';

// DBからタグの値を取得
$tag_query = "SELECT * FROM tags WHERE is_deleted=0";
$tag_result = mysqli_query($connection, $tag_query);

// DBから記事の値を取得
if (isset($_GET['post_ID'])){
    $post_ID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $post_query = "SELECT * FROM posts WHERE post_ID=$post_ID";
    $post_result = mysqli_query($connection, $post_query);
    $post = mysqli_fetch_assoc($post_result);
} else {
    header('location:'. ROOT_URL .'admin/');
    die();
}
?>

    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>記事編集</h2>
            <!-- 記事編集に失敗した場合 -->
            <?php if (isset($_SESSION['edit-post-error'])):?>    
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['edit-post-error'];
                        unset($_SESSION['edit-post-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- 記事編集フォーム -->
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="post_ID" value="<?php echo $post['post_ID'] ?>">
                <input type="hidden" name="previous_thumbnail_name" value="<?php echo $post['thumbnail'] ?>">
                <input type="text" name="title" value="<?php echo h($post['title']) ?>" placeholder="タイトル">
                <select name="tag_ID">
                    <?php while ($tag = mysqli_fetch_assoc($tag_result)): ?>
                        <option value="<?php echo $tag['tag_ID'] ?>"><?php echo $tag['tag_title'] ?></option>     
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
                <textarea rows="15" name="body" placeholder="本文"><?php echo h($post['body']) ?></textarea>
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-POST ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>