<?php
include 'partials/header.php';

// 自分以外のユーザーを読み込む
$current_admin_id = $_SESSION['user_ID'];
$query = "SELECT * FROM users WHERE user_ID!=$current_admin_id AND is_deleted=0";
$users = mysqli_query($connection, $query);
?>

    <!--================ END OF NAV ================-->

    <section class="dashboard">
        <!-- ユーザー追加に成功した場合 -->
        <?php if (isset($_SESSION['add-user-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['add-user-success'];
                    unset($_SESSION['add-user-success']); ?>
                </p>
            </div>
        <!-- ユーザー編集に成功した場合 -->
        <?php elseif (isset($_SESSION['edit-user-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['edit-user-success'];
                    unset($_SESSION['edit-user-success']); ?>
                </p>
            </div>
        <!-- ユーザー削除に成功した場合 -->
        <?php elseif (isset($_SESSION['delete-user-success'])): ?>
            <div class="alert__message success container">
                <p>
                    <?php echo $_SESSION['delete-user-success'];
                    unset($_SESSION['delete-user-success']); ?>
                </p>
            </div>
        <!-- ユーザー編集に失敗した場合 -->
        <?php elseif (isset($_SESSION['edit-user-error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['edit-user-error'];
                    unset($_SESSION['edit-user-error']); ?>
                </p>
            </div>
        <!-- ユーザー削除に失敗した場合 -->
        <?php elseif (isset($_SESSION['delete-user-error'])): ?>
            <div class="alert__message error container">
                <p>
                    <?php echo $_SESSION['delete-user-error'];
                    unset($_SESSION['delete-user-error']); ?>
                </p>
            </div>
        <?php endif; ?>
        <div class="container dashboard__container">
            <!-- メディアクエリ用ボタン途中 -->
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
            <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
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
                    <!--管理者の場合は表示-->
                    <?php if ($_SESSION['role_ID'] == 1): ?>
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/add-user.php">
                                <i class="uil uil-user-plus"></i>
                                <h5>投稿者追加</h5>
                            </a>
                        </li>                
                        <li>
                            <a href="<?php echo ROOT_URL ?>admin/manage-users.php" class="active">
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
                <h2>投稿者編集</h2>
                <?php if(mysqli_num_rows($users) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>メールアドレス</th>
                                <th>編集</th>
                                <th>削除</th>
                                <th>管理者</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = mysqli_fetch_assoc($users)): ?>
                                    <tr>
                                        <td><?php echo $user['email'] ?></td>
                                        <td><a href="<?php echo ROOT_URL ?>admin/edit-user.php?user_ID=<?php echo $user['user_ID'] ?>" class="btn sm">編集</a></td>
                                        <td><a href="<?php echo ROOT_URL ?>admin/delete-user.php?user_ID=<?php echo $user['user_ID'] ?>" class="btn sm danger">削除</a></td>
                                        <td><?php echo $user['role_ID'] ? 'はい' : 'いいえ' ?></td>
                                    </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert__message error"><?php echo "ユーザーが見つかりません"; ?></div>
                <?php endif; ?>
            </main>
        </div>
    </section>
    <!--================ END OF MANAGE-CATEGORIES ================-->
    
    <script src="../js/main.js"></script>
</body>
</html>