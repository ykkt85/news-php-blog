<?php
include 'partials/header.php';

// 検索バーから記事検索をした場合
if (isset($_GET['search']) && isset($_GET['submit'])){
    // DBから検索結果に合致するタイトルを含む記事を取得
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts WHERE is_deleted=0 AND title LIKE '%$search%' ORDER BY created_at DESC";
    $posts = mysqli_query($connection, $query);

// 検索バーから記事検索をしていない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

    <section class="search__bar">
        <form class="container search__bar-container" action="<?php echo ROOT_URL ?>search.php" method="GET">
            <div>
                <i class="uil uil-search"></i>
                <input type="search" name="search" value="<?php echo h($search) ?>" placeholder="タイトルを入力">
            </div>
            <button type="submit" name="submit" class="btn white">検索</button>
        </form>
    </section>
    <!--================ END OF SEARCH ================-->

    <!-- 検索結果に該当する記事がある場合 -->
    <?php if (mysqli_num_rows($posts) > 0): ?>
        <section class="posts section__extra-margin">
            <div class="container posts__container">
                <!-- DBから取得した記事の値を表示 -->
                <?php while($post = mysqli_fetch_assoc($posts)): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo $post['thumbnail'] ?>">
                        </div>
                        <div class="post__info">
                        <?php
                        // DBからタグデータを取得
                        $tagID = $post['tag_ID'];
                        $tagQuery = "SELECT * FROM tags WHERE tag_ID=$tagID";
                        $tagResult = mysqli_query($connection, $tagQuery);
                        $tag = mysqli_fetch_assoc($tagResult);
                        ?>
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $post['post_ID'] ?>"><?php echo h($post['title']) ?></a>
                            </h3>
                            <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="tag__button"><?php echo h($tag['tag_title']) ?></a>
                            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($post['updated_at'])) ?></small>
                            <p class="post__body">
                                <?php echo substr(h($post['body']), 0, 180) ?>...
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <!-- 検索結果に該当する記事がない場合 -->
    <?php else: ?>
        <div class="alert__message error lg section__extra-margin">
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

<?php include 'partials/footer.php'; ?>
