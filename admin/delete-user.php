<?php
require 'config/database.php';

// user_IDのセッション値を受け取った場合
if (isset($_GET['user_ID'])){
    $user_ID = filter_var($_GET['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $is_deleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $update_user_query = "UPDATE users SET is_deleted=1 WHERE user_ID=$user_ID LIMIT 1";
    $update_user_result = mysqli_query($connection, $update_user_query);

    // DBの内容を取り出す
    $fetch_user_query = "SELECT * FROM users WHERE user_ID=$user_ID LIMIT 1";
    $fetch_user_result = mysqli_query($connection, $fetch_user_query);
    $user = mysqli_fetch_assoc($fetch_user_result);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-user-success'] = "ユーザー {$user['email']} が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    
    } else {
        $_SESSION['delete-user-error'] = "ユーザー {$user['email']} の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    }

// セッション値がない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}
?>