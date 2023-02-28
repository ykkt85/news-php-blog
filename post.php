<?php
include __DIR__ . '/partials/header.php';

// post.phpのURLにpost_IDがある場合、DBから記事データを取得
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT title, category_ID, thumbnail, body, created_at FROM posts WHERE post_ID=? AND is_deleted=0');
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($title, $categoryID, $thumbnail, $body, $createdAt);
    $stmt->fetch();

// post_IDが存在しない値の場合
if (!isset($title)){
    header('location: '. ROOT_URL);
}

// post.phpのURLにpost_IDがない場合
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

<!--================================ HTML ================================-->
    
    <section class="singlepost">
        <div class="container singlepost__container">
            <?php
            // DBからタグの値を取得
            $connection = dbconnect();
            $stmt = $connection->prepare('SELECT category_title FROM categories WHERE category_ID=?');
            $stmt->bind_param('i', $categoryID);
            $stmt->execute();
            $stmt->bind_result($categoryTitle);
            $stmt->fetch();
            ?>
            <!-- 記事表示 -->
            <h2><?php echo h($title) ?></h2>
            <a href="<?php echo ROOT_URL ?>category-posts.php?category_ID=<?php echo $categoryID ?>" class="category__button"><?php echo h($categoryTitle) ?></a>
            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
            <div class="singlepost__thumbnail">
                <img src="./images/<?php echo h($thumbnail) ?>">
            </div>
            <p><?php echo h($body) ?></p>
        </div>
    </section>

    <!--================ END OF SINGLE POST ================-->
    
    <section class="category__buttons">
        <div class="container category__buttons-container">
            <?php
            // DBから全てのタグタイトルを取得
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