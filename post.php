<?php
include __DIR__ . '/partials/header.php';

// post.phpのURLにpost_IDがある場合、DBから記事データを取得
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT title, tag_ID, thumbnail, body, created_at FROM posts WHERE post_ID=? AND is_deleted=0');
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($title, $tagID, $thumbnail, $body, $createdAt);
    $stmt->fetch();

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
            $stmt = $connection->prepare('SELECT tag_title FROM tags WHERE tag_ID=?');
            $stmt->bind_param('i', $tagID);
            $stmt->execute();
            $stmt->bind_result($tagTitle);
            $stmt->fetch();
            ?>
            <!-- 記事表示 -->
            <h2><?php echo h($title) ?></h2>
            <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
            <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
            <div class="singlepost__thumbnail">
                <img src="./images/<?php echo h($thumbnail) ?>">
            </div>
            <p><?php echo h($body) ?></p>
        </div>
    </section>

    <!--================ END OF SINGLE POST ================-->
    
    <section class="tag__buttons">
        <div class="container tag__buttons-container">
            <?php
            // DBから全てのタグタイトルを取得
            $connection = dbconnect();
            $stmt = $connection->prepare('SELECT tag_ID, tag_title FROM tags WHERE is_deleted=0');
            $stmt->execute();
            $stmt->bind_result($tagID, $tagTitle);
            while($stmt->fetch()): ?>
                <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF TAGS ================-->
    
<?php
include __DIR__ . '/partials/footer.php';
?>