<?php
include __DIR__ . '/partials/header.php';

// 前回エラー時にセッション値を戻す
$categoryTitle = $_SESSION['add_category_data']['category_title'] ?? NULL;
$description = $_SESSION['add_category_data']['description'] ?? NULL;

// セッション値を消去
unset($_SESSION['add_category_data']);
?>

<!--================================ HTML ================================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>カテゴリ追加</h2>
            <?php if (isset($_SESSION['add_category_error'])): ?>
                <div class="alert__message error">
                    <p>
                        <?php
                        //インデックスを変数$iで指定
                        for($i = 0; $i < count($_SESSION['add_category_error']); $i++){
                            // 全エラーを表示
                            echo $_SESSION['add_category_error'][$i];
                            echo "<br>";
                        }                        
                        unset($_SESSION['add_category_error']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/add-category-logic.php" method="POST">
                <input type="text" name="category_title" value="<?php echo h($categoryTitle) ?>" placeholder="カテゴリ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo h($description) ?></textarea>
                <button type="submit" name="submit" class="btn purple">追加</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-category ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>