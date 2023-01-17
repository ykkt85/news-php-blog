<?php
include 'partials/header.php';

// セッション値を受け取った場合
if (isset($_GET['tag_ID'])){
    $tag_ID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);

    // DBからデータを取り出す
    $query = "SELECT * FROM tags WHERE tag_ID=$tag_ID";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) == 1){
        $tag = mysqli_fetch_assoc($result);
    }

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
                <input type="hidden" name="tag_ID" value="<?php echo $tag['tag_ID']; ?>">
                <input type="text" name="tag_title" value="<?php echo $tag['tag_title']; ?>" placeholder="タグ名">
                <textarea rows="4" name="description" placeholder="説明"><?php echo $tag['description']; ?></textarea>
                <button type="submit" name="submit" class="btn purple">保存</button>
            </form>
        </div>
    </section>
    <!--================ END OF ADD-CATEGORY ================-->
    
    <script src="../js/main.js"></script>
</body>
</html>