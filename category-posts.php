<?php
include __DIR__ . '/partials/header.php';

// category-posts.phpのURLにcategory_IDがある場合、カテゴリと関連付けられている記事を表示
if (isset($_GET['category_ID'])){
    $categoryID = filter_var($_GET['category_ID'], FILTER_SANITIZE_NUMBER_INT);

// category-posts.phpのURLにcategory_IDがない場合
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
                <input type="search" name="search" placeholder="単語等を入力">
            </div>
            <button type="submit" name="submit" class="btn white">検索</button>
        </form>
    </section>
    <!--================ END OF SEARCH ================-->

    <header class="category__title">
        <?php
        // DBからカテゴリの値を取得
        $connection = dbconnect();
        $categoryStmt = $connection->prepare('SELECT category_title FROM categories WHERE category_ID=? AND is_deleted=0');
        $categoryStmt->bind_param('i', $categoryID);
        $categoryStmt->execute();
        $categoryStmt->bind_result($barcategoryTitle);
        $categoryStmt->fetch();
        ?>
        <h2><?php echo h($barcategoryTitle) ?></h2>
    </header>
    <!--================ END OF category TITLE ================-->

    <!-- 該当カテゴリの付いた記事がある場合 -->
    <?php //if ($postResult): ?>
        <section class="posts">
            <div class="container posts__container">
                <!-- 記事を表示 -->
                <?php
                $connection = dbconnect();
                $postStmt = $connection->prepare('SELECT post_ID, title, thumbnail, body, created_at FROM posts WHERE category_ID=? AND is_deleted=0 ORDER BY updated_at DESC');
                $postStmt->bind_param('i', $categoryID);
                $success = $postStmt->execute();
                $postStmt->bind_result($postID, $title, $thumbnail, $body, $createdAt);
                while($postStmt->fetch()): ?>
                    <article class="post">
                        <div class="post__thumbnail">
                            <img src="./images/<?php echo h($thumbnail) ?>">
                        </div>
                        <div class="post__info">
                            <h3 class="post__title">
                                <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $postID ?>"><?php echo h($title) ?></a>
                            </h3>
                            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
                            <p class="post__body">
                                <?php echo substr(h($body), 0, 180) ?>...
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <!-- 該当カテゴリの付いた記事がない場合 -->
    <?php //else: ?>
        <!--<div class ="alert__message error lg">
            <p>記事がありません</p>
        </div>-->
    <?php //endif; ?>
    <!--================ END OF POSTS ================-->
    
    <section class="category__buttons">
        <div class="container category__buttons-container">
            <?php
            // DBから全てのカテゴリタイトルを取得
            $connection = dbconnect();
            $stmt = $connection->prepare('SELECT category_ID, category_title FROM categories WHERE is_deleted=0');
            $stmt->execute();
            $stmt->bind_result($categoryID, $categoryTitle);
            while($stmt->fetch()): ?>
                <a href="<?php echo ROOT_URL ?>category-posts.php?category_ID=<?php echo $categoryID ?>" class="category__button"><?php echo h($categoryTitle) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF CATEGORIES ================-->

<?php
include __DIR__ . '/partials/footer.php';
?>