<?php
include __DIR__ . '/partials/header.php';
?>

<!--================================ HTML ================================-->

    <section class="dashboard">
        <!-- 新規記事投稿に成功した場合 -->
        <?php if (isset($_SESSION['add_post_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['add_post_success'];
                    unset($_SESSION['add_post_success']); ?>
                </p>
            </div>
        <!-- 記事編集に成功した場合 -->
        <?php elseif (isset($_SESSION['edit_post_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['edit_post_success'];
                    unset($_SESSION['edit_post_success']); ?>
                </p>
            </div>
        <!-- 記事編集に失敗した場合 -->
        <?php elseif (isset($_SESSION['edit_post_error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['edit_post_error'];
                    unset($_SESSION['edit_post_error']); ?>
                </p>
            </div>
        <!-- 記事削除に成功した場合 -->
        <?php elseif (isset($_SESSION['delete_post_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['delete_post_success'];
                    unset($_SESSION['delete_post_success']); ?>
                </p>
            </div>
        <!-- 記事削除に失敗した場合 -->
        <?php elseif (isset($_SESSION['delete_post_error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['delete_post_error'];
                    unset($_SESSION['delete_post_error']); ?>
                </p>
            </div>
        <!-- パスワード変更に成功した場合 -->
        <?php //elseif (isset($_SESSION['new_password_success'])): ?>
            <!-- <div class="alert__message success container">
                <p>
                    <?php /*echo $_SESSION['new_password_success'];
                    unset($_SESSION['new_password_success']);*/ ?>
                </p>
            </div> -->
        <?php endif; ?>
        <div class="container dashboard__container">
            <!-- メディアクエリ用ボタン途中 -->
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <!-- メディアクエリ用ボタン途中 ここまで -->
            <!-- サイドバー -->
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
                    <!-- ログイン中のユーザーが管理者の場合は表示 -->
                    <?php if ($_SESSION['role_ID'] == 1): ?>              
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/manage-users.php">
                                <i class="uil uil-users-alt"></i>
                                <h5>投稿者編集</h5>
                            </a>
                        </li>                
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/add-category.php">
                                <i class="uil uil-label-alt"></i>
                                <h5>新規タグ</h5>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/manage-categories.php">
                                <i class="uil uil-list-ul"></i>
                                <h5>タグ編集</h5>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- <li>
                        <a href="<?php //echo ROOT_URL ?>admin/change-email.php">
                            <i class="uil uil-envelope-edit"></i>                            
                            <h5>メールアドレス変更</h5>
                        </a>
                    </li>
                    <li>
                        <a href="<?php //echo ROOT_URL ?>admin/change-password.php">
                            <i class="uil uil-key-skeleton-alt"></i>
                            <h5>パスワード変更</h5>
                        </a>
                    </li> -->
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
                <?php
                // 現在ログイン中のユーザーのメールアドレスを取得
                $currentUserID = $_SESSION['user_ID'];
                $connection = dbconnect();
                $emailStmt = $connection->prepare('SELECT email from users WHERE user_ID=?');
                $emailStmt->bind_param('i', $currentUserID);
                $emailSuccess = $emailStmt->execute();
                $emailStmt->bind_result($email);
                $emailStmt->fetch();
                ?>
                <p>現在ログイン中のユーザー：<?php echo h($email) ?></p>
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
                        <?php
                        //記事を表示
                        $connection = dbconnect();
                        $postStmt = $connection->prepare('SELECT p.post_ID, p.title, t.category_title FROM posts AS p INNER JOIN categories AS t ON p.category_ID=t.category_ID WHERE p.user_ID=? AND p.is_deleted=0 ORDER BY p.updated_at DESC');
                        $postStmt->bind_param('i', $currentUserID);
                        $postStmt->execute();
                        $postStmt->bind_result($postID, $title, $categoryTitle);
                        while($postStmt->fetch()): ?>
                        <tr>
                            <td><?php echo h($title) ?></td>
                            <td><?php echo h($categoryTitle) ?></td>
                            <td><a href="<?php echo ROOT_URL ?>admin/edit-post.php?post_ID=<?php echo $postID ?>" class="btn sm">編集</a></td>
                            <td><a href="<?php echo ROOT_URL ?>admin/delete-post.php?post_ID=<?php echo $postID ?>" class="btn sm danger">削除</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!--<div class="alert__message error"><?php //echo "投稿はありません"; ?></div>-->
            </main>
        </div>
    </section>
    <!--================ END OF INDEX (MANAGE-POSTS) ================-->

    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>