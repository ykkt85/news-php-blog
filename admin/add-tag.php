<?php
include 'partials/header.php';

// エラー時にセッションデータを戻す
$tag_title = $_SESSION['add-tag-data']['tag_title'] ?? NULL;
$description = $_SESSION['add-tag-data']['description'] ?? NULL;

// セッションデータを消去
unset($_SESSION['add-tag-data']);
?>
    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>タグ追加</h2>
            <?php if (isset($_SESSION['add-tag-error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['add-tag-error'];
                        unset($_SESSION['add-tag-error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-tag-logic.php" method="POST">
                <input type="text" name="tag_title" value="<?php echo $tag_title ?>" placeholder="タグ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo $description ?></textarea>
                <button type="submit" name="submit" class="btn purple">追加</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-CATEGORY ================-->

    <script src="../js/main.js"></script>
</body>
</html>