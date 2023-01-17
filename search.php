<?php
include 'partials/header.php';

// 検索バーの単語かつボタンクリックを確認したとき
if (isset($_GET['search']) && isset($_GET['submit'])){
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts WHERE is_deleted=0 AND title LIKE '%$search%' ORDER BY created_at DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

    <?php if (mysqli_num_rows($posts) > 0): ?>
        <section class="posts section__extra-margin">
            <div class="container posts__container">
                <?php while($post = mysqli_fetch_assoc($posts)): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo $post['thumbnail'] ?>">
                        </div>
                        <div class="post__info">
                        <?php
                        // DBからタグデータを取得
                        $tag_ID = $post['tag_ID'];
                        $tag_query = "SELECT * FROM tags WHERE tag_ID=$tag_ID";
                        $tag_result = mysqli_query($connection, $tag_query);
                        $tag = mysqli_fetch_assoc($tag_result);
                        ?>
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $post['post_ID'] ?>"><?php echo $post['title'] ?></a>
                            </h3>
                            <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="category__button"><?php echo $tag['tag_title'] ?></a>
                            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($post['updated_at'])) ?></small>
                            <p class="post__body">
                                <?php echo substr($post['body'], 0, 180) ?>...
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <?php else: ?>
        <div class="alert__message error lg section__extra-margin">
            <p>記事がありません</p>
        </div>
    <?php endif; ?>
    <!--================ END OF POSTS ================-->

    <section class="category__buttons">
        <div class="container category__buttons-container">
            <?php
            $all_tags_query = "SELECT * FROM tags WHERE is_deleted=0";
            $all_tags = mysqli_query($connection, $all_tags_query);
            ?>
            <?php while($tag = mysqli_fetch_assoc($all_tags)): ?>
                <a href="<?php echo ROOT_URL ?>tag_posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="category__button"><?php echo $tag['tag_title'] ?></a>
            <?php endwhile; ?>
            </div>
    </section>
    <!--================ END OF TAGS ================-->

<?php include 'partials/footer.php'; ?>
