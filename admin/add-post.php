<?php
include 'partials/header.php';

$query = "SELECT * FROM tags WHERE is_deleted=0";
$tags = mysqli_query($connection, $query);


// エラー時にセッションデータを戻す
$title = $_SESSION['add-post-data']['title'] ?? NULL;
$body = $_SESSION['add-post-data']['body'] ?? NULL;

// セッションデータを消去
unset($_SESSION['add-post-data']);
?>
    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>新規投稿</h2>
            <!-- 新規投稿に失敗した場合 -->
            <?php if (isset($_SESSION['add-post-error'])):?>    
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['add-post-error'];
                        unset($_SESSION['add-post-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-post-logic.php" enctype="multipart/form-data" method="POST">
                <input type="text" name="title" value="<?php echo $title ?>" placeholder="タイトル">
                <select name="tag_ID">
                    <?php while($tag = mysqli_fetch_assoc($tags)): ?>
                        <option value="<?php echo $tag['tag_ID'] ?>"><?php echo $tag['tag_title'] ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="form__control inline">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" checked>
                    <label for="is_featured">注目記事</label>
                </div>
                <div class="form__control">
                    <label for="thumbnail">写真を追加</label>
                    <input type="file" name="thumbnail" id="thumbnail">
                </div>
                <textarea rows="15" name="body" placeholder="本文"><?php echo $body ?></textarea>
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-CATEGORY ================-->
    
    <script src="../js/main.js"></script>
</body>
</html>