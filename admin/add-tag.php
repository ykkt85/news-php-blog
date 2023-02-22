<?php
include __DIR__ . '/partials/header.php';

// 前回エラー時にセッションデータを戻す
$tagTitle = $_SESSION['add_tag_data']['tag_title'] ?? NULL;
$description = $_SESSION['add_tag_data']['description'] ?? NULL;

// セッションデータを消去
unset($_SESSION['add_tag_data']);
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>タグ追加</h2>
            <?php if (isset($_SESSION['add_tag_error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php echo $_SESSION['add_tag_error'];
                        unset($_SESSION['add_tag_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-tag-logic.php" method="POST">
                <input type="text" name="tag_title" value="<?php echo h($tagTitle) ?>" placeholder="タグ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo h($description) ?></textarea>
                <button type="submit" name="submit" class="btn purple">追加</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-TAG ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>