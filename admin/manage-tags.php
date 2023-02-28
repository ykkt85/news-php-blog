<?php
include __DIR__ . '/partials/header.php';

// 管理者以外（投稿者）がアクセスした場合
if ($_SESSION['role_ID'] === 0){
    $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
    header('location: ' . ROOT_URL . 'message.php');
    die();
}

// 登録してあるタグを読み込む
$connection = dbconnect();
$stmt = $connection->prepare('SELECT tag_ID, tag_title, description FROM tags WHERE is_deleted=0');
$success = $stmt->execute();
$stmt->bind_result($tagID, $tagTitle, $description);
?>

<!--================================ HTML ================================-->

    <section class="dashboard">
        <!-- タグ追加に成功した場合 -->
        <?php if (isset($_SESSION['add_tag_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['add_tag_success'];
                    unset($_SESSION['add_tag_success']); ?>
                </p>
            </div>
        <!-- タグ編集に成功した場合 -->
        <?php elseif (isset($_SESSION['edit_tag_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['edit_tag_success'];
                    unset($_SESSION['edit_tag_success']); ?>
                </p>
            </div>
        <!-- タグ削除に成功した場合 -->
        <?php elseif (isset($_SESSION['delete_tag_success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['delete_tag_success'];
                    unset($_SESSION['delete_tag_success']); ?>
                </p>
            </div>
        <!-- タグ編集に失敗した場合 -->
        <?php elseif (isset($_SESSION['edit_tag_error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['edit_tag_error'];
                    unset($_SESSION['edit_tag_error']); ?>
                </p>
            </div>
        <!-- タグ削除に失敗した場合 -->
        <?php elseif (isset($_SESSION['delete_tag_error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['delete_tag_error'];
                    unset($_SESSION['delete_tag_error']); ?>
                </p>
            </div>
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
                        <a href="<?php echo ROOT_URL ?>admin/index.php">
                            <i class="uil uil-newspaper"></i>
                            <h5>投稿編集</h5>
                        </a>
                    </li>
                    <!--ログイン中のユーザーが管理者の場合は表示-->
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
                            <a href="<?php echo ROOT_URL ?>admin/manage-tags.php" class="active">
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
                <h2>タグ編集</h2>
                <!-- タグが登録されている場合 -->
                    <table>
                        <thead>
                            <tr>
                                <th>タグ</th>
                                <th>説明</th>
                                <th>編集</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- タグ表示 -->
                            <?php while($stmt->fetch()): ?>
                                <tr>
                                    <td><?php echo h($tagTitle) ?></td>
                                    <td><?php echo h($description) ?></td>
                                    <td><a href="<?php echo ROOT_URL ?>admin/edit-tag.php?tag_ID=<?php echo $tagID ?>" class="btn sm">編集</a></td>
                                    <td><a href="<?php echo ROOT_URL ?>admin/delete-tag.php?tag_ID=<?php echo $tagID ?>" class="btn sm danger">削除</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <!-- タグが登録されていないとき -->
                <?php //else: ?>
                    <!--<div class="alert__message error"><?php //echo "タグが登録されていません"; ?></div>-->
                <?php //endif; ?>
            </main>
        </div>
    </section>
    <!--================ END OF MANAGE-TAGS ================-->
    
    <script src="<?php echo ROOT_URL ?>js/main.js"></script>
</body>
</html>