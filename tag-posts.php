<?php
include 'partials/header.php';

// tag_IDがある場合、当該タグの付いた記事を取得
if (isset($_GET['tag_ID'])){
    $ID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE tag_ID=$ID AND is_deleted=0 ORDER BY updated_at DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>
    <!--================ END OF NAV ================-->

    <section class="search__bar">
        <form class="container search__bar-container" action="">
            <div>
                <i class="uil uil-search"></i>
                <input type="search" name="" placeholder="Search">
            </div>
            <button type="submit" class="btn">Go</button>
        </form>
    </section>
    <!--================ END OF SEARCH ================-->

    <header class="category__title">
        <?php
        // DBからタグデータを取得
        $tag_ID = $ID;
        $tag_query = "SELECT * FROM tags WHERE tag_ID=$tag_ID AND is_deleted=0";
        $tag_result = mysqli_query($connection, $tag_query);
        $tag = mysqli_fetch_assoc($tag_result);
        ?>
        <h2><?php echo $tag['tag_title'] ?></h2>
    </header>
    <!--================ END OF CATEGORY TITLE ================-->

    <?php if (mysqli_num_rows($posts) > 0): ?>
        <section class="posts">
            <div class="container posts__container">
                <?php while($post = mysqli_fetch_assoc($posts)): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo $post['thumbnail'] ?>">
                        </div>
                        <div class="post__info">
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $post['post_ID'] ?>"><?php echo $post['title'] ?></a>
                            </h3>
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
        <div class ="alert__message error lg">
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

<?php
include 'partials/footer.php';
?>