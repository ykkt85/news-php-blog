<?php
include 'partials/header.php';

// DBから注目記事を取得
$connection = dbconnect();
$stmt = $connection->prepare('SELECT p.post_ID, p.title, p.tag_ID, p.thumbnail, p.body, p.created_at, t.tag_title FROM posts AS p INNER JOIN tags AS t ON p.tag_ID=t.tag_ID WHERE p.is_featured=1 AND p.is_deleted=0');
$stmt->execute();
$stmt->bind_result($postID, $title, $tagID, $thumbnail, $body, $createdAt, $tagTitle);
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

    <!-- 注目記事があれば表示 -->
    <?php if ($stmt->fetch()): ?>
        <section class="featured">
            <div class="container featured__container">
                <div class="post__thumbnail">
                    <img src="./images/<?php echo h($thumbnail) ?>">
                </div>
                <div class="post__info">
                    <h2 class="post__title"><a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $postID ?>"><?php echo h($title) ?></a></h2>
                    <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
                    <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
                    <p class="post__body">
                        <?php echo substr(h($body), 0, 180) ?>...
                    </p>
                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- 注目記事がない場合は余白を追加 -->
        <section class="posts section__extra-margin">
    <?php endif; ?>
    <!--================ END OF FEATURED ================-->

        <div class="container posts__container">
            <?php
            // DBから記事を取得
            $connection = dbconnect();
            $stmt = $connection->prepare('SELECT p.post_ID, p.title, p.tag_ID, p.thumbnail, p.body, p.created_at, t.tag_title FROM posts AS p INNER JOIN tags AS t ON p.tag_ID=t.tag_ID WHERE p.is_featured=0 AND p.is_deleted=0 ORDER BY p.created_at DESC');
            $stmt->execute();
            $stmt->bind_result($postID, $title, $tagID, $thumbnail, $body, $createdAt, $tagTitle);
            while($stmt->fetch()):
            ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="./images/<?php echo h($thumbnail) ?>">
                    </div>
                    <div class="post__info">
                        <h3 class="post__title">
                            <a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $postID ?>"><?php echo h($title) ?></a>
                        </h3>
                        <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
                        <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($createdAt)) ?></small>
                        <p class="post__body">
                            <?php echo substr(h($body), 0, 180) ?>...
                        </p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF POSTS ================-->
    
    <section class="tag__buttons">
        <div class="container tag__buttons-container">
            <?php
            // DBから全てのタグタイトルを取得
            $connection = dbconnect();
            $stmt = $connection->prepare('SELECT tag_ID, tag_title FROM tags WHERE is_deleted=0');
            $stmt->execute();
            $stmt->bind_result($tagID, $tagTitle);
            while($stmt->fetch()):
            ?>
                <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tagID ?>" class="tag__button"><?php echo h($tagTitle) ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <!--================ END OF TAGS ================-->

<?php
include 'partials/footer.php';
?>