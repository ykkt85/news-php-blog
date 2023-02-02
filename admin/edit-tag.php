<?php
include 'partials/header.php';

// edit-tag.phpのURLからtag_IDの値を受け取った場合
if (isset($_GET['tag_ID'])){
    $tagID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);

    // DBから値を取り出す
    $query = "SELECT * FROM tags WHERE tag_ID=$tagID";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) == 1){
        $tag = mysqli_fetch_assoc($result);
    }

// URLからtag_IDの値を受け取っていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>
    <!--================ END OF NAV ================-->

    <section class="form__section">
        <div class="container form__section-container">
            <h2>タグ編集</h2>
            <form class="form__column" action="<?php echo ROOT_URL ?>admin/edit-tag-logic.php" method="POST">
                <input type="hidden" name="tag_ID" value="<?php echo $tag['tag_ID'] ?>">
                <input type="text" name="tag_title" value="<?php echo h($tag['tag_title']) ?>" placeholder="タグ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo h($tag['description']) ?></textarea>
                <button type="submit" name="submit" class="btn purple">保存</button>
            </form>
        </div>
    </section>
    <!--================ END OF EDIT-TAG ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>