<?php
include __DIR__ . '/partials/header.php';
$connection = dbconnect();

// タグ表示のためDBからデータを取得
$stmt = $connection->query('SELECT * FROM categorys WHERE is_deleted=0');

// 前回エラー時にセッションデータを表示
$title = $_SESSION['add_post_data']['title'] ?? NULL;
$body = $_SESSION['add_post_data']['body'] ?? NULL;

// セッションデータを消去
unset($_SESSION['add_post_data']);
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>新規記事</h2>
            <!-- 新規記事投稿に失敗した場合 -->
            <?php if (isset($_SESSION['add_post_error'])):?>    
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['add_post_error'];
                        unset($_SESSION['add_post_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <!-- 記事投稿 -->
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-post-logic.php" enctype="multipart/form-data" method="POST">
                <input type="text" name="title" value="<?php echo h($title) ?>" placeholder="タイトル">
                <select name="category_ID">
                    <?php while($category = $stmt->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_ID'] ?>"><?php echo $category['category_title'] ?></option>
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
                <textarea rows="15" name="body" placeholder="本文"><?php echo h($body) ?></textarea>
                <button type="submit" name="submit" class="btn purple">投稿する</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-POST ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>