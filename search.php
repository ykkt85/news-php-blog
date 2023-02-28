<?php
include __DIR__ . '/partials/header.php';
$connection = dbconnect();

// 検索バーから記事検索をした場合
if (isset($_GET['search'])){
    // DBから検索結果に合致するタイトルを含む記事を取得
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $stmt = $connection->prepare("SELECT p.post_ID, p.title, p.category_ID, p.thumbnail, p.body, p.created_at, t.category_title FROM posts AS p INNER JOIN categories AS t ON p.category_ID=t.category_ID WHERE p.is_deleted=0 AND p.body LIKE CONCAT('%', ?, '%') ORDER BY p.created_at DESC");
    $stmt->bind_param('s', $search);
    $stmt->execute();
    $stmt->bind_result($postID, $title, $categoryID, $thumbnail, $body, $createdAt, $categoryTitle);

// 検索バーから記事検索をしていない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

<!--================================ HTML ================================-->

    <section class="search__bar">
        <form class="container search__bar-container" action="<?php echo ROOT_URL ?>search.php" method="GET">
            <div>
                <i class="uil uil-search"></i>
                <input type="search" name="search" value="<?php echo h($search) ?>" placeholder="単語等を入力">
            </div>
            <button type="submit" name="submit" class="btn white">検索</button>
        </form>
    </section>
    <!--================ END OF SEARCH ================-->

    <!-- 検索結果に該当する記事がある場合 -->
    <?php //if ($stmt->fetch()): ?>
        <section class="posts section__extra-margin">
            <div class="container posts__container">
                <!-- DBから取得した記事の値を表示（表示されない） -->
                <?php while($stmt->fetch()): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo $thumbnail ?>">
                        </div>
                        <div class="post__info">
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $postID ?>"><?php echo h($title) ?></a>
                            </h3>
                            <a href="<?php echo ROOT_URL ?>category-posts.php?category_ID=<?php echo $categoryID ?>" class="category__button"><?php echo h($categoryTitle) ?></a>
                            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
                            <p class="post__body">
                                <?php echo substr(h($body), 0, 180) ?>...
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <!-- 検索結果に該当する記事がない場合 -->
    <?php //else: ?>
        <!-- <div class="alert__message error lg section__extra-margin">
            <p>記事がありません</p>
        </div> -->
    <?php //endif; ?>
    <!--================ END OF POSTS ================-->

    <section class="category__buttons">
        <div class="container category__buttons-container">
            <?php
            // DBから全てのタグタイトルを取得
            $stmt = $connection->prepare('SELECT category_ID, category_title FROM categories WHERE is_deleted=0');
            $stmt->execute();
            $stmt->bind_result($categoryID, $categoryTitle);
            while($stmt->fetch()): ?>
                <a href="<?php echo ROOT_URL ?>category-posts.php?category_ID=<?php echo $categoryID ?>" class="category__button"><?php echo h($categoryTitle) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF CATEGORIES ================-->

<?php include __DIR__ . '/partials/footer.php'; ?>
