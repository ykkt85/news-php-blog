<?php
require 'config/database.php';

// URLにpost_IDの値が含まれている場合
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isDeleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの値を上書き
    $updatePostQuery = "UPDATE posts SET is_deleted=1 WHERE post_ID=$postID LIMIT 1";
    $updatePostResult = mysqli_query($connection, $updatePostQuery);

    // DBの値を取り出す
    $fetchPostQuery = "SELECT * FROM posts WHERE post_ID=$postID LIMIT 1";
    $fetchPostResult = mysqli_query($connection, $fetchPostQuery);
    $post = mysqli_fetch_assoc($fetchPostResult);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-post-success'] = "投稿「 " . h($post['title']) . "」が削除されました";
        header('location: ' . ROOT_URL . 'admin/');
    
    // エラーがある場合
    } else {
        $_SESSION['delete-post-error'] = "投稿「 ". h($post['title']) . " 」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/');
    }

// URLにpost_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>