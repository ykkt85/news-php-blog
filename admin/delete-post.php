<?php
require 'config/database.php';

if (isset($_GET['post_ID'])){
    $post_ID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $is_deleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $update_post_query = "UPDATE posts SET is_deleted=1 WHERE post_ID=$post_ID LIMIT 1";
    $update_post_result = mysqli_query($connection, $update_post_query);

    // DBの内容を取り出す
    $fetch_post_query = "SELECT * FROM posts WHERE post_ID=$post_ID LIMIT 1";
    $fetch_post_result = mysqli_query($connection, $fetch_post_query);
    $post = mysqli_fetch_assoc($fetch_post_result);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-post-success'] = "投稿「 {$post['title']} 」が削除されました";
        header('location: ' . ROOT_URL . 'admin/');
    
    // エラーがある場合
    } else {
        $_SESSION['delete-post-error'] = "投稿「 {$post['title']} 」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/');
    }

// セッション値がない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>