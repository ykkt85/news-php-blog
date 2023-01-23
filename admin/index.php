<?php
include 'partials/header.php';

// ログイン中のユーザーが投稿した記事を取得
$current_user_id = $_SESSION['user_ID'];
$post_query = "SELECT post_ID, title, tag_ID FROM posts WHERE user_ID=$current_user_id AND is_deleted=0 ORDER BY updated_at DESC";
$posts = mysqli_query($connection, $post_query);
$post = mysqli_fetch_assoc($posts);
?>

    <!--================ END OF NAV ================-->

    <section class="dashboard">
        <!-- 新規記事投稿に成功した場合 -->
        <?php if (isset($_SESSION['add-post-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['add-post-success'];
                    unset($_SESSION['add-post-success']); ?>
                </p>
            </div>
        <!-- 記事編集に成功した場合 -->
        <?php elseif (isset($_SESSION['edit-post-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['edit-post-success'];
                    unset($_SESSION['edit-post-success']); ?>
                </p>
            </div>
        <!-- 記事編集に失敗した場合 -->
        <?php elseif (isset($_SESSION['edit-post-error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['edit-post-error'];
                    unset($_SESSION['edit-post-error']); ?>
                </p>
            </div>
        <!-- 記事削除に成功した場合 -->
        <?php elseif (isset($_SESSION['delete-post-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['delete-post-success'];
                    unset($_SESSION['delete-post-success']); ?>
                </p>
            </div>
        <!-- 記事削除に失敗した場合 -->
        <?php elseif (isset($_SESSION['delete-post-error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['delete-post-error'];
                    unset($_SESSION['delete-post-error']); ?>
                </p>
            </div>
        <!-- パスワード変更に成功した場合 -->
        <?php elseif (isset($_SESSION['new-password-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['new-password-success'];
                    unset($_SESSION['new-password-success']); ?>
                </p>
            </div>
        <?php endif; ?>
        <div class="container dashboard__container">
            <!-- メディアクエリ用ボタン途中 -->
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <!-- メディアクエリ用ボタン途中 ここまで -->
            <aside>
                <ul>
                    <li>
                        <a href="<?php echo ROOT_URL ?>admin/add-post.php">
                            <i class="uil uil-pen"></i>
                            <h5>新規投稿</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ROOT_URL ?>admin/index.php" class="active">
                            <i class="uil uil-newspaper"></i>
                            <h5>記事編集</h5>
                        </a>
                    </li>
                    <!--管理者の場合は表示-->
                    <?php if ($_SESSION['role_ID'] == 1): ?>              
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/manage-users.php">
                                <i class="uil uil-users-alt"></i>
                                <h5>投稿者編集</h5>
                            </a>
                        </li>                
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/add-tag.php">
                                <i class="uil uil-label-alt"></i>
                                <h5>新規タグ</h5>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/manage-tags.php">
                                <i class="uil uil-list-ul"></i>
                                <h5>タグ編集</h5>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!--<li>
                        <a href="<?php echo ROOT_URL ?>admin/change-email.php">
                            <i class="uil uil-envelope-edit"></i>                            
                            <h5>メールアドレス変更</h5>
                        </a>
                    </li>-->
                    <li>
                        <a href="<?php echo ROOT_URL ?>admin/change-password.php">
                            <i class="uil uil-key-skeleton-alt"></i>
                            <h5>パスワード変更</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ROOT_URL ?>logout.php">
                            <i class="uil uil-signout"></i>
                            <h5>ログアウト</h5>
                        </a>
                    </li>
                </ul>
            </aside>
            <main>
                <h2>記事編集</h2>
                    <!-- 現在ログイン中のユーザーのメールアドレスを取得 -->
                    <?php
                    $user_query = "SELECT email from users WHERE user_ID=$current_user_id";
                    $users = mysqli_query($connection, $user_query);
                    $user = mysqli_fetch_assoc($users);
                    ?>
                <p>現在ログイン中のユーザー：<?php echo $user['email'] ?></p>
                <?php if(mysqli_num_rows($posts) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>見出し</th>
                                <th>タグ</th>
                                <th>編集</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 投稿記事を表示 -->
                            <?php while($post = mysqli_fetch_assoc($posts)): ?>
                                <!-- tagタイトルを取得 -->
                                <?php
                                $tag_ID = $post['tag_ID'];
                                $tag_query = "SELECT tag_title from tags WHERE tag_ID=$tag_ID";
                                $tags = mysqli_query($connection, $tag_query);
                                $tag = mysqli_fetch_assoc($tags);
                                ?>
                                <tr>
                                    <td><?php echo $post['title'] ?></td>
                                    <td><?php echo $tag['tag_title'] ?></td>
                                    <td><a href="<?php echo ROOT_URL ?>admin/edit-post.php?post_ID=<?php echo $post['post_ID'] ?>" class="btn sm">編集</a></td>
                                    <td><a href="<?php echo ROOT_URL ?>admin/delete-post.php?post_ID=<?php echo $post['post_ID'] ?>" class="btn sm danger">削除</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <!-- 投稿記事がない場合 -->
                <?php else: ?>
                    <div class="alert__message error"><?php echo "投稿はありません"; ?></div>
                <?php endif; ?>
            </main>
        </div>
    </section>
    <!--================ END OF MANAGE-CATEGORIES ================-->

    <script src="../js/main.js"></script>
</body>
</html>