<?php
require 'config/database.php';

// user_IDのセッション値を受け取った場合
if (isset($_GET['user_ID'])){
    $userID = filter_var($_GET['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isDeleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $updateUserQuery = "UPDATE users SET is_deleted=1 WHERE user_ID=$userID LIMIT 1";
    $updateUserResult = mysqli_query($connection, $updateUserQuery);

    // DBの内容を取り出す
    $fetchUserQuery = "SELECT * FROM users WHERE user_ID=$userID LIMIT 1";
    $fetchUserResult = mysqli_query($connection, $fetchUserQuery);
    $user = mysqli_fetch_assoc($fetchUserResult);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-user-success'] = "ユーザー {$user['email']} が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    // エラーがある場合
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