<?php
include 'partials/header.php';

// 記事idがある場合、DBから記事データを取得
if (isset($_GET['post_ID'])){
    $post_ID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE post_ID=$post_ID AND is_deleted=0";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

    <!--================ END OF NAV ================-->
    
    <section class="singlepost">
        <div class="container singlepost__container">
            <?php
            // DBからタグデータを取得
            $tag_ID = $post['tag_ID'];
            $tag_query = "SELECT * FROM tags WHERE tag_ID=$tag_ID";
            $tag_result = mysqli_query($connection, $tag_query);
            $tag = mysqli_fetch_assoc($tag_result);
            ?>
            <h2><?php echo $post['title'] ?></h2>
            <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="category__button"><?php echo $tag['tag_title'] ?></a>
            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($post['updated_at'])) ?></small>
            <div class="singlepost__thumbnail">
                <img src="./images/<?php echo $post['thumbnail'] ?>">
            </div>
            <p><?php echo $post['body'] ?></p>
        </div>
    </section>

    <!--================ END OF SINGLE POST ================-->
    
<?php
include 'partials/footer.php';
?>