<?php
require __DIR__ . '/../config/database.php';

// edit-user.phpのフォームから値が送信された場合
if (isset($_POST['submit'])){
    $userID = filter_var($_POST['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $roleID = filter_var($_POST['role_ID'], FILTER_SANITIZE_NUMBER_INT);

    // 管理者以外（投稿者）がアクセスした場合、または
    // ログインユーザーが本人のedit-user-logic.phpにアクセスしようとした場合
    if ($_SESSION['role_ID'] === 0 || $_SESSION['user_ID'] === $userID){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

    // DBの値を上書き保存
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE users SET role_ID=?, updated_at=CURRENT_TIMESTAMP() WHERE user_ID=? LIMIT 1');
    $stmt->bind_param('ii', $roleID, $userID);
    $success = $stmt->execute();

    // エラーがない場合
    if (!$success){
        $_SESSION['edit_user_error'] = "内容変更に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    } else {
        $_SESSION['edit_user_success'] = "変更内容が保存されました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    }

// edit-user.phpのフォームから値が送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}