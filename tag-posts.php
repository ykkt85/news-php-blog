<?php
include 'partials/header.php';

// tag-posts.phpのURLにtag_IDがある場合、該当タグの付いた記事を取得
if (isset($_GET['tag_ID'])){
    $ID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE tag_ID=$ID AND is_deleted=0 ORDER BY updated_at DESC";
    $posts = mysqli_query($connection, $query);
    
// tag-posts.phpのURLにtag_IDがない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>
    <!--================ END OF NAV ================-->

    <section class="search__bar">
        <form class="container search__bar-container" action="<?php echo ROOT_URL ?>search.php" method="GET">
            <div>
                <i class="uil uil-search"></i>
                <input type="search" name="search" placeholder="タイトルを入力">
            </div>
            <button type="submit" name="submit" class="btn white">検索</button>
        </form>
    </section>
    <!--================ END OF SEARCH ================-->

    <header class="tag__title">
        <?php
        // DBからタグの値を取得
        $tagID = $ID;
        $tagQuery = "SELECT * FROM tags WHERE tag_ID=$tagID AND is_deleted=0";
        $tagResult = mysqli_query($connection, $tagQuery);
        $tag = mysqli_fetch_assoc($tagResult);
        ?>
        <h2><?php echo $tag['tag_title'] ?></h2>
    </header>
    <!--================ END OF TAG TITLE ================-->

    <!-- 該当タグの付いた記事がある場合 -->
    <?php if (mysqli_num_rows($posts) > 0): ?>
        <section class="posts">
            <div class="container posts__container">
                <!-- 記事を表示 -->
                <?php while($post = mysqli_fetch_assoc($posts)): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo h($post['thumbnail']) ?>">
                        </div>
                        <div class="post__info">
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $post['post_ID'] ?>"><?php echo h($post['title']) ?></a>
                            </h3>
                            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($post['updated_at'])) ?></small>
                            <p class="post__body">
                                <?php echo substr(h($post['body']), 0, 180) ?>...
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <!-- 該当タグの付いた記事がない場合 -->
    <?php else: ?>
        <div class ="alert__message error lg">
            <p>記事がありません</p>
        </div>
    <?php endif; ?>
    <!--================ END OF POSTS ================-->
    
    <section class="tag__buttons">
        <div class="container tag__buttons-container">
            <?php
            $allTagsQuery = "SELECT * FROM tags WHERE is_deleted=0";
            $allTags = mysqli_query($connection, $allTagsQuery);
            ?>
            <?php while($tag = mysqli_fetch_assoc($allTags)): ?>
                <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="tag__button"><?php echo h($tag['tag_title']) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF TAGS ================-->

<?php
include 'partials/footer.php';
?>