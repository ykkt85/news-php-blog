<?php
require 'config/database.php';

// 変更ボタンを押した場合
if (isset($_POST['submit'])){
    $user_ID = filter_var($_POST['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $role_ID = filter_var($_POST['role_ID'], FILTER_SANITIZE_NUMBER_INT);

    // DBの内容を上書き保存
    $query = "UPDATE users SET role_ID=$role_ID, updated_at=CURRENT_TIMESTAMP() WHERE user_ID=$user_ID LIMIT 1";
    $result = mysqli_query($connection, $query);

    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['edit-user-success'] = "変更内容が保存されました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    
    } else {
        $_SESSION['edit-user-error'] = "内容変更に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    }

} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}