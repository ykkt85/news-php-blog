<?php
include 'partials/header.php';

// post.phpのURLにpost_IDがある場合、DBから記事データを取得
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE post_ID=$postID AND is_deleted=0";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);

// post.phpのURLにpost_IDがない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

    <!--================ END OF NAV ================-->
    
    <section class="singlepost">
        <div class="container singlepost__container">
            <?php
            // DBからタグの値を取得
            $tagID = $post['tag_ID'];
            $tagQuery = "SELECT * FROM tags WHERE tag_ID=$tagID";
            $tagResult = mysqli_query($connection, $tagQuery);
            $tag = mysqli_fetch_assoc($tagResult);
            ?>
            <!-- 記事表示 -->
            <h2><?php echo h($post['title']) ?></h2>
            <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="tag__button"><?php echo h($tag['tag_title']) ?></a>
            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($post['updated_at'])) ?></small>
            <div class="singlepost__thumbnail">
                <img src="./images/<?php echo h($post['thumbnail']) ?>">
            </div>
            <p><?php echo h($post['body']) ?></p>
        </div>
    </section>

    <!--================ END OF SINGLE POST ================-->
    
<?php
include 'partials/footer.php';
?>