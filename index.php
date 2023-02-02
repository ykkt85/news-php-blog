<?php
include 'partials/header.php';

// DBから注目記事を取得
$featuredQuery = "SELECT * FROM posts WHERE is_featured=1 AND is_deleted=0";
$featuredResult = mysqli_query($connection, $featuredQuery);
$featured = mysqli_fetch_assoc($featuredResult);

// DBから記事を取得
$query = "SELECT * FROM posts WHERE is_deleted=0 ORDER BY created_at DESC";
$posts = mysqli_query($connection, $query);
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

    <!-- 注目記事があれば表示 -->
    <?php if (mysqli_num_rows($featuredResult) == 1): ?>
        <section class="featured">
            <div class="container featured__container">
                <div class="post__thumbnail">
                    <img src="./images/<?php echo h($featured['thumbnail']) ?>">
                </div>
                <div class="post__info">
                    <?php
                    // DBからタグデータを取得
                    $tagID = $featured['tag_ID'];
                    $tagQuery = "SELECT * FROM tags WHERE tag_ID=$tagID";
                    $tagResult = mysqli_query($connection, $tagQuery);
                    $tag = mysqli_fetch_assoc($tagResult);
                    ?>
                    <h2 class="post__title"><a href="<?php echo ROOT_URL ?>post.php?post_ID=<?php echo $featured['post_ID'] ?>"><?php echo h($featured['title']) ?></a></h2>
                    <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="tag__button"><?php echo h($tag['tag_title']) ?></a>
                    <small class="publish__date"><?php echo date("Y.m.d - H:i", strtotime($featured['updated_at'])) ?></small>
                    <p class="post__body">
                        <?php echo substr(h($featured['body']), 0, 180) ?>...
                    </p>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <!--================ END OF FEATURED ================-->

    <!-- 注目記事がない場合は余白を追加 -->
    <section class="posts<?php $featured ? ' ' : 'section__extra-margin' ?>">
        <div class="container posts__container">
            <?php while($post = mysqli_fetch_assoc($posts)): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="./images/<?php echo h($post['thumbnail']) ?>">
                    </div>
                    <div class="post__info">
                    <?php
                    // DBからタグの値を取得
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
    <!--================ END OF POSTS ================-->
    
    <section class="tag__buttons">
        <div class="container tag__buttons-container">
            <?php
            // DBからタグの値を取得
            $allTagsQuery = "SELECT * FROM tags WHERE is_deleted=0";
            $allTags = mysqli_query($connection, $allTagsQuery);
            // 登録されているタグがある場合
            while($tag = mysqli_fetch_assoc($allTags)): ?>
                <a href="<?php echo ROOT_URL ?>tag-posts.php?tag_ID=<?php echo $tag['tag_ID'] ?>" class="tag__button"><?php echo h($tag['tag_title']) ?></a>
            <?php endwhile; ?>
            </div>
    </section>
    <!--================ END OF TAGS ================-->

    <?php
    include 'partials/footer.php';
    ?>