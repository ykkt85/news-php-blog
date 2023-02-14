<?php
include 'partials/header.php';

// tag-posts.phpのURLにtag_IDがある場合、タグと関連付けられている記事を表示
if (isset($_GET['tag_ID'])){
    $tagID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);

// tag-posts.phpのURLにtag_IDがない場合
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

    <header class="tag__title">
        <?php
        // DBからタグの値を取得
        $connection = dbconnect();
        $tagStmt = $connection->prepare('SELECT tag_title FROM tags WHERE tag_ID=? AND is_deleted=0');
        $tagStmt->bind_param('i', $tagID);
        $tagStmt->execute();
        $tagStmt->bind_result($barTagTitle);
        $tagStmt->fetch();
        ?>
        <h2><?php echo h($barTagTitle) ?></h2>
    </header>
    <!--================ END OF TAG TITLE ================-->

    <!-- 該当タグの付いた記事がある場合 -->
    <?php //if ($postResult): ?>
        <section class="posts">
            <div class="container posts__container">
                <!-- 記事を表示 -->
                <?php
                $connection = dbconnect();
                $postStmt = $connection->prepare('SELECT post_ID, title, thumbnail, body, created_at FROM posts WHERE tag_ID=? AND is_deleted=0 ORDER BY updated_at DESC');
                $postStmt->bind_param('i', $tagID);
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
    <!-- 該当タグの付いた記事がない場合 -->
    <?php //else: ?>
        <!--<div class ="alert__message error lg">
            <p>記事がありません</p>
        </div>-->
    <?php //endif; ?>
    <!--================ END OF POSTS ================-->
    
    <section class="tag__buttons">
        <div class="container tag__buttons-container">
            <?php
            // DBから全てのタグタイトルを取得
            $connection = dbconnect();
            $downTagStmt = $connection->prepare('SELECT tag_ID, tag_title FROM tags WHERE is_deleted=0');
            $downTagStmt->execute();
            $downTagStmt->bind_result($tagID, $tagTitle);
            while($downTagStmt->fetch()): ?>
                <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF TAGS ================-->

<?php
include 'partials/footer.php';
?>